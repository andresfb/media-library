<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

trait TagsCacheable
{
    public function getAllTags()
    {
        $key = 'ALL:TAGS:%s';
        $tags = Cache::get($key);
        if (!empty($tags)) {
            return $tags;
        }

        $tags = DB::table('tags')
            ->select('name')
            ->cache(now()->addMinutes(5), sprintf($key, 'Q'))
            ->get()
            ->pluck('name')
            ->map(function ($tag) {
                return json_decode($tag)->en;
            })->sort();

        if ($tags->isEmpty()) {
            return collect([]);
        }

        Cache::put(sprintf($key, 'L'), $tags, now()->addMinutes(10));
        return $tags;
    }
}
