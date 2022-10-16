<?php

namespace App\Traits;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

trait MediaAccessible
{
    /**
     * updateAccess Method.
     *
     * @param string $path
     * @return void
     */
    public function updateAccess(string $path): void
    {
        if (!file_exists($path)) {
            mkdir($path, 0775, true);
        }

        $dir = dirname($path);
        $this->changeAccess($dir);

        $parts = Str::of($dir)
            ->replace(config('filesystems.disks.media.root'), '')
            ->trim()
            ->explode("/")
            ->reject(function ($item) {
                return empty($item);
            });

        while ($parts->isNotEmpty()) {
            $path = sprintf("%s/%s", config('filesystems.disks.media.root'), $parts->implode("/"));
            $this->changeAccess($path);
            $parts->pop();
        }
    }

    /**
     * changeAccess Method.
     *
     * @param string $path
     * @return void
     */
    private function changeAccess(string $path): void
    {
        if (!file_exists($path)) {
            mkdir($path, 0775, true);
            return;
        }

        try {
            chmod($path, 0775);
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }
}
