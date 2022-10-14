<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use LaravelIdea\Helper\App\Models\_IH_Post_QB;

class PostsService
{
    /**
     * getLatest Method.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getLatest(int $perPage): LengthAwarePaginator
    {
        return Post::select('posts.*')
            ->whereStatus(0)
            ->with('tags')
            ->join('items', 'posts.item_id', '=', 'items.id')
            ->where('items.active', true)
            ->with('item')
            ->with('item.media')
            ->inRandomOrder()
            ->limit((int) config('posts.max_daily_posts'))
            ->paginate($perPage);
    }
}
