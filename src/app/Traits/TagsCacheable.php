<?php

namespace App\Traits;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

trait TagsCacheable
{
    /**
     * getAllTags Method.
     *
     * @return Collection
     */
    public function getAllTags(): Collection
    {
        $key = config('cache-constants.tags_key');
        $tags = Cache::get($key);
        if (!empty($tags)) {
            return $tags;
        }

        $tags = DB::table('tags')
            ->select('name')
            ->cache(now()->addMinutes(5), config('cache-constants.query_tags_key'))
            ->get()
            ->pluck('name')
            ->map(function ($tag) {
                return json_decode($tag)->en;
            })->sort();

        if ($tags->isEmpty()) {
            return collect([]);
        }

        Cache::put($key, $tags, now()->addMinutes(10));
        return $tags;
    }

    /**
     * clearCache Method.
     *
     * @return void
     */
    public function clearCache(): void
    {
        // Clear the Tags list cache
        $key = config('cache-constants.tags_key');
        Cache::forget($key);

        // Clear the Query Tags list cache
        $key = config('cache-constants.query_tags_key');
        Cache::forget($key);
    }
}
