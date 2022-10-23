<?php

namespace App\ViewModels;

use App\Models\Media;
use App\Models\Post;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection as SimpleCollection;
use Spatie\ViewModels\ViewModel;

class TaggedPostListViewModel extends ViewModel
{
    public int $postCount = 0;

    public SimpleCollection $selected;

    public ?LengthAwarePaginator $postList;

    private SimpleCollection $posts;


    public function __construct(?LengthAwarePaginator $postList, SimpleCollection $tags, int $postCount)
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

    /**
     * removeTag Method.
     *
     * @param $value
     * @return string
     */
    public function removeTag(string $value): string
    {
        if (!$this->selected->contains($value)) {
            return "";
        }

        return $this->selected->reject(function ($tag) use ($value) {
            return $tag == $value;
        })->implode(",");
    }

    /**
     * addTag Method.
     *
     * @param string $value
     * @return string
     */
    public function addTag(string $value): string
    {
        if ($this->selected->contains($value)) {
            return $this->selected->implode(",");
        }

        $tags = collect($this->selected->all());

        return $tags->add($value)
            ->implode(",");
    }
}
