<?php

namespace App\Services;

use App\Models\Post;
use App\Traits\TagsCacheable;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class PostsService
{
    use TagsCacheable;

    private Collection $tags;

    public function __construct()
    {
        $this->tags = collect([]);
    }

    /**
     * getTagged Method.
     *
     * @param array $values
     * @param int $perPage
     * @return array
     */
    public function getTagged(array $values, int $perPage = 0): array
    {
        if (empty($perPage)) {
            $perPage = config("posts.per_page") * 2;
        }

        $tags = $this->parseTags($values['tags'] ?? '');
        if (empty($tags)) {
            return [null, [], 0];
        }

        $query = Post::with('item')
            ->with('item.media')
            ->with('tags')
            ->with('comments')
            ->withAllTags($tags);

        if (!empty($values['type'])) {
            $query->where('posts.type', $values['type']);
        }

        $count = $query->count();

        return [
            $query->cache(600)->limit($perPage)->get(),
            $tags,
            $count
        ];
    }


    /**
     * parseTags Method.
     *
     * @param string $tags
     * @return array
     */
    private function parseTags(string $tags): array
    {
        $tags = Str::of($tags)->trim();
        if ($tags->isEmpty()) {
            return [];
        }

        return $tags->lower()
            ->explode(',')
            ->map(function ($tag) {
                $tag = trim($tag);
                return $this->getTags()->contains($tag) ? $tag : '';
            })->reject(function ($tag) {
                return empty($tag);
            })->toArray();
    }

    /**
     * getTags Method.
     *
     * @return Collection
     */
    private function getTags(): Collection
    {
        if ($this->tags->isNotEmpty()) {
            return $this->tags;
        }

        $this->tags = $this->getAllTags();
        return $this->tags;
    }
}
