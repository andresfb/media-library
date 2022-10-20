<?php

namespace App\Traits;

use App\Models\Feed;
use App\Models\Post;

trait PostFeedFindable
{
    /**
     * getModels Method.
     *
     * @param int $postId
     * @return bool|array
     */
    public function getModels(int $postId): bool|array
    {
        $post = Post::find($postId);
        if (empty($post)) {
            session()->flash("error", "Post not found with Id: $postId");
            return false;
        }

        $feed = Feed::where('id', $postId)->first();
        if (empty($feed)) {
            session()->flash("error", "Feed not found with Id: $postId");
            return false;
        }

        return [$post, $feed];
    }
}
