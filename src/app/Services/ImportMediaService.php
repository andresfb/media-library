<?php

namespace App\Services;

use App\Models\Duplicate;
use App\Models\Item;
use Exception;
use Illuminate\Support\Facades\Log;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use SplFileInfo;

class ImportMediaService
{
    /** @var array */
    private array $baseFolders = [];

    /** @var int */
    private int $maxFiles = 0;

    /** @var int */
    private int $scanned = 0;

    /** @var int */
    private int $imported = 0;


    /**
     * execute Method.
     *
     * @return void
     * @throws Exception
     */
    public function execute(): void
    {
        Log::info("Media import started at " . now()->toDateTimeString());

        $this->maxFiles = (int) config('raw-files.max_files');
        $path = $this->getLatestImported();
        $files = $this->scanPath($path);

        if (empty($files)) {
            throw new Exception("No files found to import");
        }

        $this->importFiles($files);

        Log::info("Media import started at " . now()->toDateTimeString() . ". Imported {$this->imported}");
    }


    /**
     * getLatestImported Method.
     *
     * @return string
     */
    private function getLatestImported(): string
    {
        $item = Item::orderByDesc("id")
            ->orderByDesc('created_at')
            ->first();

        if (empty($item)) {
            return '00001';
        }

        return $item->og_path;
    }

    /**
     * scanPath Method.
     *
     * @param string $path
     * @return array
     */
    private function scanPath(string $path): array
    {
        $basePath = config('raw-files.path') . $path;
        $this->baseFolders = glob(config('raw-files.path') . "*", GLOB_ONLYDIR);
        sort($this->baseFolders);

        $position = array_search($basePath, $this->baseFolders);
        if ($position === false) {
            return [];
        }

        $files = [];
        return $this->scanFiles($position, $files);
    }

    /**
     * scanFiles Method.
     *
     * @param int $position
     * @param array $files
     * @return array
     */
    private function scanFiles(int $position, array $files): array
    {
        if (!array_key_exists($position, $this->baseFolders)) {
            return $files;
        }

        $baseFolder = $this->baseFolders[$position];
        $scans = array_diff(scandir($baseFolder, SCANDIR_SORT_ASCENDING), ['.', '..']);
        if (empty($scans)) {
            return $this->scanFiles(++$position, $files);
        }

        foreach ($scans as $scan) {
            if (str_starts_with($scan, config('raw-files.exclude'))) {
                continue;
            }

            if ($this->scanned >= $this->maxFiles) {
                return $files;
            }

            $fullFile = sprintf("%s/%s", $baseFolder, $scan);
            $hash = hash_file('md5', $fullFile);
            $path = str_replace(config('raw-files.path'), '', $baseFolder);

            if (array_key_exists($hash, $files)) {
                continue;
            }

            if (Item::found($hash, $path)) {
                continue;
            }

            $item = Item::whereHash($hash)->first();
            if (!empty($item)) {
                $this->saveDuplicate($item->id, $hash, $path, $fullFile);
                continue;
            }

            $files[$hash] = $fullFile;
            $this->scanned++;
        }

        return $this->scanFiles(++$position, $files);
    }

    /**
     * importFiles Method.
     *
     * @param array $files
     * @return void
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    private function importFiles(array $files): void
    {
        foreach ($files as $hash => $file) {
            $fileInfo = new SplFileInfo($file);
            $path = str_replace(config('raw-files.path'), '', $fileInfo->getPath());

            $type = getimagesize($file) ? "image" : "video";
            $item = Item::updateOrCreate([
                'hash' => $hash,
                'og_path' => $path,
            ], [
                'og_file' => $fileInfo->getFilename(),
                'type' => $type
            ]);

            $item->events()->updateOrCreate([
                'item_id' => $item->id,
                'action' => 'imported'
            ],[
                'requester' => ImportMediaService::class
            ]);

            $item->addMedia($file)
                ->preservingOriginal()
                ->toMediaCollection($type);

            $item->events()->updateOrCreate([
                'item_id' => $item->id,
                'action' => 'media added'
            ],[
                'requester' => ImportMediaService::class
            ]);

            $this->imported++;
        }
    }

    /**
     * saveDuplicate Method.
     *
     * @param int $itemId
     * @param string $fileHash
     * @param string $path
     * @param string $fullFile
     * @return void
     */
    private function saveDuplicate(int $itemId, string $fileHash, string $path, string $fullFile): void
    {
        $fileInfo = new SplFileInfo($fullFile);
        Duplicate::updateOrCreate([
            'item_id' => $itemId,
            'hash' => $fileHash,
            'og_path' => $path,
        ],[
            'og_file' => $fileInfo->getFilename(),
        ]);
    }
}
