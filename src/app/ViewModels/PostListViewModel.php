<?php

namespace App\ViewModels;

use App\Models\Item;
use App\Models\Media;
use App\Models\Post;
use App\Services\AvatarGeneratorService;
use Exception;
use FFMpeg\FFProbe;
use GrahamCampbell\Markdown\Facades\Markdown;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\URL;
use Spatie\Tags\Tag;
use Spatie\ViewModels\ViewModel;
use Illuminate\Support\Collection as SimpleCollection;
use SplFileInfo;

class PostListViewModel extends ViewModel
{
    private LengthAwarePaginator $postList;

    private AvatarGeneratorService $service;

    public function __construct(LengthAwarePaginator $postList)
    {
        $this->postList = $postList;
        $this->service = resolve(AvatarGeneratorService::class);
    }

    /**
     * posts Method.
     *
     * @return SimpleCollection
     */
    public function posts(): SimpleCollection
    {
        return $this->postList->map(function (Post $post) {

            if (empty($post->item->media)) {
                return [];
            }

            $media = $post->item->media->where('collection_name', $post->type)->first();
            if (empty($media)) {
                return [];
            }

            $avatar = $this->service->getAvatar();
            return [
                'name' => $avatar['name'],
                'avatar' => $avatar['image'],
                'id' => $post->id,
                'media' => $this->generateLink($media),
                'og_file_name' => $post->item->og_file,
                'og_location' => sprintf("%s%s", config('raw-files.path'), $post->item->og_path),
                'type' => $post->type,
                'slug' => $post->slug,
                'title' => $post->title,
                'content' => Markdown::convert($post->content)->getContent(),
                'tags' => $post->tags->map(function (Tag $tag) {
                    return [
                        'id' => $tag->id,
                        'tag' => $tag->name,
                        'slug' => $tag->slug
                    ];
                }),
            ];
        })->collect();
    }

    /**
     * generateLink Method.
     *
     * @param Media $media
     * @return string
     */
    private function generateLink(Media $media): string
    {
        return URL::temporarySignedRoute(
            'preview', // route name
            now()->addMinutes(45), // TTL
            ['media' => $media->id] // object id
        );
    }
}
