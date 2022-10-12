<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use LaravelIdea\Helper\App\Models\_IH_Post_QB;

class PostsService
{
    private string $usedKey;

    private string $postsKey;

    private int $maxPosts ;


    public function __construct()
    {
        $this->usedKey = md5("USED:POSTS");
        $this->postsKey = md5("LATEST:POSTS:%s" . date('Y-m-d'));
        $this->maxPosts = (int) config('posts.max_daily_posts');
    }

    /**
     * getLatest Method.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getLatest(int $perPage): LengthAwarePaginator
    {
        /** @var LengthAwarePaginator $posts */
        $posts = Cache::get($this->postsKey);
        if (empty($posts)) {
            return $this->loadPosts($this->maxPosts, $perPage);
        }

        $used = Cache::get($this->usedKey);
        if (empty($used)) {
            return $this->loadPosts($this->maxPosts, $perPage);
        }

        $posts = $posts->shift($used)
            ->append($this->loadPosts($used, $perPage));

        Cache::put(config('posts.cache_posts') ? $posts : [], now()->addDay());
        return $posts;
    }

    /**
     * loadPosts Method.
     *
     * @param int $maxPosts
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    private function loadPosts(int $maxPosts, int $perPage): LengthAwarePaginator
    {
        return Post::select('posts.*')
            ->whereStatus(0)
            ->with('tags')
            ->join('items', 'posts.item_id', '=', 'items.id')
            ->where('items.active', true)
            ->with('item')
            ->with('item.media')
            ->inRandomOrder()
            ->limit($maxPosts)
            ->paginate($perPage);
    }
}
