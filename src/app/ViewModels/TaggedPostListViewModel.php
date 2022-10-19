<?php

namespace App\ViewModels;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Spatie\Tags\Tag;

class TaggedPostListViewModel extends PostListViewModel
{
    private Collection $tags;

    public array $selected;

    public function __construct(LengthAwarePaginator $postList, array $tags, int $postCount)
    {
        parent::__construct($postList, $postCount);
        $this->selected = $tags;
        $this->tags = collect([]);
    }

    /**
     * tags Method.
     *
     * @return Collection
     */
    public function tags(): Collection
    {
        if ($this->tags->isNotEmpty()) {
            return $this->tags;
        }

        $this->tags = Tag::select('name')
            ->cache(3600)
            ->get()
            ->pluck('name')
            ->sort();

        return $this->tags;
    }
}
