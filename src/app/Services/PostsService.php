<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class PostsService
{
    private string $usedKey;

    private string $postsKey;

    private int $maxPosts ;


    public function __construct()
    {
        $this->usedKey = "USED:POSTS";
        $this->postsKey = "LATEST:POSTS:" . date('Y-m-d');
        $this->maxPosts = (int) config('posts.max_daily_posts');
    }

    /**
     * getLatest Method.
     *
     * @return Collection
     */
    public function getLatest(): Collection
    {
        /** @var Collection $posts */
        $posts = Cache::get($this->postsKey);
        if (empty($posts)) {
            return $this->loadPosts($this->maxPosts);
        }

        $used = Cache::get($this->usedKey);
        if (empty($used)) {
            return $this->loadPosts($this->maxPosts);
        }

        $posts = $posts->shift($used)
            ->append($this->loadPosts($used));

        Cache::put($posts, now()->addDay());
        return $posts;
    }

    /**
     * loadPosts Method.
     *
     * @param int $maxPosts
     * @return Collection
     */
    private function loadPosts(int $maxPosts): Collection
    {
        return Post::whereStatus(0)
            ->with('item')
            ->with('image.media')
            ->inRandomOrder()
            ->limit($maxPosts)
            ->get();
    }
}
