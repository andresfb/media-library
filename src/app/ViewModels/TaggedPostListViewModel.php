<?php

namespace App\ViewModels;


use Illuminate\Database\Eloquent\Collection;

class TaggedPostListViewModel extends PostListViewModel
{
    public array $selected;

    public function __construct(?Collection $postList, array $tags, int $postCount)
    {
        parent::__construct($postList, $postCount);
        $this->selected = $tags;
    }
}
