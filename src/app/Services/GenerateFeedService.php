<?php

namespace App\Services;

use App\Models\Feed;
use App\Models\Media;
use App\Models\Post;
use Exception;
use GrahamCampbell\Markdown\Facades\Markdown;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class GenerateFeedService
{
    private int $generated = 0;

    private AvatarGeneratorService $service;


    public function __construct(AvatarGeneratorService $service)
    {
        $this->service = $service;
    }

    /**
     * execute Method.
     *
     * @param int $howMany
     * @return void
     */
    public function execute(int $howMany = 0): void
    {
        if (empty($howMany)) {
            $howMany = (int) config('posts.max_daily_feed');
        }

        Log::info("Generating started. Generating ($howMany) Feed records.");

        $posts = Post::select('posts.*')
            ->unused($howMany)
            ->with('tags')
            ->join('items', 'posts.item_id', '=', 'items.id')
            ->where('items.active', true)
            ->with('item')
            ->with('item.media')
            ->with('comments')
            ->get();

        if ($posts->isEmpty()) {
            Log::info("No new Post found.");
            return;
        }

        $this->generateFeed($posts, false);
        Log::info("Generating Feed finished at Generated {$this->generated}");
    }

    /**
     * generateFeed Method.
     *
     * @param Collection $posts
     * @param boolean $postStatus
     * @return void
     */
    public function generateFeed(Collection $posts, bool $postStatus): void
    {
        if ($posts->isEmpty()) {
            Log::info("No new Post found.");
            return;
        }

        /** @var Post $post */
        foreach ($posts as $post) {
            try {
                [$postId, $tags, $postData] = $this->getFeed($post, $postStatus);
                if (empty($postId)) {
                    continue;
                }

                $this->saveFeed($postId, $postData, $tags);

                $post->used = true;
                $post->save();

                $this->generated++;
                if (app()->runningInConsole()) {
                    echo ".";
                }
            } catch (Exception $e) {
                Log::error($e->getMessage());
                if (app()->runningInConsole()) {
                    echo $e->getMessage() . PHP_EOL;
                }
            }
        }
    }

    /**
     * getFeed Method.
     *
     * @param Post $post
     * @param bool $postStatus
     * @return array
     */
    public function getFeed(Post $post, bool $postStatus = false): array
    {
        if (empty($post->item->media)) {
            return [0, [], []];
        }

        /** @var Media $media */
        $media = $post->item->getMedia($post->type)->first();
        if (empty($media)) {
            return [0, [], []];
        }

        $poster = "";
        $thumb = $post->item->getMedia('thumb')->first();
        if (!empty($thumb)) {
            $poster = $thumb->getUrl();
        }

        $measurement = "KB";
        $fileSize = ceil($media->size / 1024);
        if ($fileSize > 999) {
            $fileSize = ceil($fileSize / 1024);
            $measurement = "MB";
        }

        $avatar = $this->service->getAvatar();
        $extra = [
            'Post Id' => $post->id,
            'Original Location' => sprintf("%s%s/", config('raw-files.path'), $post->item->og_path),
            'Original File' => $post->item->og_file,
            'File Size' => number_format($fileSize) . " $measurement",
            'Imported On' => $post->item->created_at->toDateTimeString(),
        ];

        if (!empty($post->item->exif)) {
            $extra = array_merge($extra, $post->item->exif);
        }

        $comments = [];
        foreach ($post->comments as $comment) {
            $comments[] = [
                'date' => $comment->created_at->toDateTimeString(),
                'comment' => $comment->comment,
            ];
        }

        return [
            $post->id,
            $post->tags->pluck('name')->sort()->toArray(),
            [
                'name' => $avatar['name'],
                'avatar' => $avatar['image'],
                'media' => $media->getUrl(),
                'poster' => $poster,
                'mime_type' => $media->mime_type,
                'aspect' => $extra['aspect'] ?? '1x1',
                'status' => $postStatus ? $post->status : 0,
                'type' => $post->type,
                'slug' => $post->slug,
                'title' => $post->title,
                'source' => $post->source,
                'content' => Markdown::convert($post->content)->getContent(),
                'date' => $post->created_at,
                'extra_info' => $extra,
                'comments' => $comments,
                'tags' => [],
            ]
        ];
    }

    /**
     * saveFeed Method.
     *
     * @param int $postId
     * @param array $postData
     * @param array $tags
     * @return Feed
     */
    public function saveFeed(int $postId, array $postData, array $tags): Feed
    {
        $feed = Feed::updateOrCreate(['id' => $postId], $postData);
        foreach ($tags as $tag) {
            $feed->push('tags', $tag);
        }

        $feed->save();
        return $feed;
    }
}
