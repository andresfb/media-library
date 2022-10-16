<?php

namespace App\Services;

use App\Models\Item;
use Exception;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\FFMpeg;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CreateThumbnailService
{
    /**
     * execute Method.
     *
     * @param int $itemId
     * @return void
     * @throws Exception
     */
    public function execute(int $itemId): void
    {
        $item = Item::with('media')->findOrFail($itemId);
        if (empty($item->media) || $item->media->isEmpty()) {
            return;
        }

        $file = $item->getMedia($item->type)->first()->getPath();
        if (!file_exists($file)) {
            return;
        }

        $destination = storage_path('app/process/') . md5(Str::random()) . ".jpg";

        $ffmpeg = FFMpeg::create();
        $vid = $ffmpeg->open($file);
        $vid->frame(TimeCode::fromSeconds(1))
            ->save($destination, true);

        if (!file_exists($destination)) {
            throw new Exception("Couldn't create thumbnail from Item: " . $item->id);
        }

        $item->addMedia($destination)
            ->toMediaCollection('thumb');

        Log::info("Generated the thumbnail for video Item: $itemId");
    }
}
