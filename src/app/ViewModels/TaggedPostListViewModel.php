<?php

namespace App\ViewModels;

use App\Models\Media;
use App\Models\Post;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SimpleCollection;
use Spatie\ViewModels\ViewModel;

class TaggedPostListViewModel extends ViewModel
{
    public int $postCount = 0;

    public array $selected;

    private ?Collection $postList;

    private SimpleCollection $posts;

    public function __construct(?Collection $postList, array $tags, int $postCount)
    {
        $this->postList = $postList;
        $this->selected = $tags;
        $this->postCount = $postCount;
        $this->posts = collect([]);
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

            return [
                'id' => $post->id,
                'media' => $media->getUrl(),
                'poster' => $poster,
                'mime_type' => $media->mime_type,
                'aspect' => $extra['aspect'] ?? '1x1',
                'type' => $post->type,
                'source' => $post->source,
                'tags' => $post->tags->pluck('name')->sort(),
            ];
        })->collect();

        return $this->posts;
    }
}
