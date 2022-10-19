<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

trait TagsCacheable
{
    public function getAllTags()
    {
        $key = 'ALL:TAGS';
        $tags = Cache::get($key);
        if (!empty($tags)) {
            return $tags;
        }

        $tags = DB::table('tags')
            ->select('name')
            ->cache(300)
            ->get()
            ->pluck('name')
            ->map(function ($tag) {
                return json_decode($tag)->en;
            })->sort();

        if ($tags->isEmpty()) {
            return collect([]);
        }

        Cache::put($key, $tags, now()->addSeconds(600));
        return $tags;
    }
}
