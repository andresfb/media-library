<?php

namespace App\ViewModels;

use App\Models\Media;
use App\Models\Post;
use App\Services\AvatarGeneratorService;
use GrahamCampbell\Markdown\Facades\Markdown;
use Illuminate\Database\Eloquent\Collection;
use Spatie\ViewModels\ViewModel;
use Illuminate\Support\Collection as SimpleCollection;

class PostListViewModel extends ViewModel
{
    private ?Collection $postList;

    private AvatarGeneratorService $service;

    private SimpleCollection $posts;

    public int $postCount = 0;

    public function __construct(?Collection $postList, int $postCount)
    {
        $this->postList = $postList;
        $this->service = resolve(AvatarGeneratorService::class);
        $this->posts = collect([]);
        $this->postCount = $postCount;
    }

    /**
     * posts Method.
     *
     * @return SimpleCollection
     */
    public function posts(): SimpleCollection
    {
        if ($this->posts->isNotEmpty()) {
            return $this->posts;
        }

        if (empty($this->postList)) {
            return collect([]);
        }

        $this->posts = $this->postList->map(function (Post $post) {

            if (empty($post->item->media)) {
                return [];
            }

            /** @var Media $media */
            $media = $post->item->getMedia($post->type)->first();
            if (empty($media)) {
                return [];
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
                'Original Location' => sprintf("%s%s/", config('raw-files.path'), $post->item->og_path),
                'Original File' => $post->item->og_file,
                'File Size' => number_format($fileSize) . " $measurement",
                'Imported On' => $post->item->created_at->toDateTimeString()
            ];

            if (!empty($post->item->exif)) {
                $extra = array_merge($extra, $post->item->exif);
            }

            $comments = [];
            foreach ($post->comments as $comment) {
                $comments[] = [
                    'date' => $comment->created_at->longAbsoluteDiffForHumans(),
                    'comment' => $comment->comment,
                ];
            }

            return [
                'id' => $post->id,
                'name' => $avatar['name'],
                'avatar' => $avatar['image'],
                'media' => $media->getUrl(),
                'poster' => $poster,
                'mime_type' => $media->mime_type,
                'aspect' => $extra['aspect'] ?? '1x1',
                'type' => $post->type,
                'slug' => $post->slug,
                'title' => $post->title,
                'source' => $post->source,
                'content' => Markdown::convert($post->content)->getContent(),
                'date' => $post->created_at->longAbsoluteDiffForHumans(),
                'extra_info' => $extra,
                'comments' => collect($comments),
                'tags' => $post->tags->pluck('name')->sort(),
            ];
        })->collect();

        return $this->posts;
    }
}
