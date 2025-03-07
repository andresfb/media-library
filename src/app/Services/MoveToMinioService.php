<?php

namespace App\Services;

use App\Models\Item;
use App\Models\ItemFile;
use App\Models\Media;
use Illuminate\Support\Facades\Storage;

class MoveToMinioService
{
    private bool $toScreen = false;

    public function execute(int $itemId): void
    {
        $item = Item::where('id', $itemId)
            ->with('media')
            ->firstOrFail();

        if ($this->toScreen) {
            echo "Processing Item: $itemId - $item->og_file\n";
        }

        $mediaInfo = $this->getMediaFile($item);
        if (empty($mediaInfo)) {
            throw new \RuntimeException("Media not found $itemId");
        }

        if ($this->toScreen){
            echo "Found file {$mediaInfo['file']}\n";
        }

        $mediaInfo['url'] = $this->pushToMinio($mediaInfo['file']);
        if (empty($mediaInfo['url'])) {
            throw new \RuntimeException('Could not push file to minio');
        }

        if ($this->toScreen) {
            echo "File saved to {$mediaInfo['url']}\n";
        }

        $this->saveItemFile($itemId, $mediaInfo);

        if ($this->toScreen) {
            echo "Data saved...\n\n";
        }
    }

    public function setToScreen(bool $toScreen): self
    {
        $this->toScreen = $toScreen;
        return $this;
    }

    private function getMediaFile(Item $item): array
    {
        /** @var Media $media */
        foreach ($item->media as $media) {
            if ($media->collection_name === 'thumb') {
                continue;
            }

            $file = "/" . $media->getPathRelativeToRoot();

            return [
                'file' => $file,
                'file_name' => pathinfo($file, PATHINFO_FILENAME),
                'mime_type' => $media->mime_type,
                'size' => $media->size,
                'type' => $media->collection_name,
                'created_at' => $media->created_at,
                'updated_at' => $media->updated_at,
            ];
        }

        return [];
    }

    private function pushToMinio(string $file): string
    {
        Storage::disk('s3')->put(
            $file,
            Storage::disk('media')->get($file)
        );

        return Storage::disk('s3')->url($file);
    }

    private function saveItemFile(int $itemId, array $mediaInfo): void
    {
        unset($mediaInfo['file']);

        ItemFile::updateOrCreate(
            ['item_id' => $itemId],
            $mediaInfo
        );
    }
}
