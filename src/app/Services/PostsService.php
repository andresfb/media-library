<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Pagination\LengthAwarePaginator;

class PostsService
{
    /**
     * getLatest Method.
     *
     * @param array $values
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getLatest(array $values, int $perPage = 0): LengthAwarePaginator
    {
        if (empty($perPage)) {
            $perPage = config("posts.per_page");
        }

        $query = Post::select('posts.*')
            ->whereStatus(0)
            ->with('tags')
            ->join('items', 'posts.item_id', '=', 'items.id')
            ->where('items.active', true)
            ->with('item')
            ->with('item.media')
            ->inRandomOrder()
            ->limit((int) config('posts.max_daily_posts'));

        if (!empty($values['type'])) {
            $query->where('posts.type', $values['type']);
        }

        return $query->paginate($perPage);
    }
}
