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
     * @param int $howMany
     * @return void
     * @throws Exception
     */
    public function execute(int $howMany = 0): void
    {
        Log::info("Media import started at " . now()->toDateTimeString());

        $this->maxFiles = $howMany ?? (int) config('raw-files.max_files');
        $path = $this->getLatestImported();
        $files = $this->scanPath($path);

        if (empty($files)) {
            throw new Exception("No files found to import");
        }

        $this->importFiles($files);

        Log::info("Media import finished at "
            . now()->toDateTimeString()
            . ". Imported {$this->imported}"
        );
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
     */
    private function importFiles(array $files): void
    {
        foreach ($files as $hash => $file) {
            try {
                $fileInfo = new SplFileInfo($file);
                $path = str_replace(config('raw-files.path'), '', $fileInfo->getPath());
                $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                $type = $extension == 'heic'
                    ? 'heic'
                    : (getimagesize($file) ? "image" : "video");

                $ogItem = Item::select('id')
                    ->whereHash($hash)
                    ->first()
                    ->pluck('id')
                    ->toArray();

                $item = Item::updateOrCreate([
                    'hash' => $hash,
                    'og_path' => $path,
                ], [
                    'og_file' => $fileInfo->getFilename(),
                    'type' => $type,
                    'active' => true,
                    'og_item_id' => $ogItem ?? null,
                ]);

                $item->addMedia($file)
                    ->preservingOriginal()
                    ->toMediaCollection($type);

                $this->imported++;
            } catch (Exception $e) {
                Log::error($e->getMessage());
            }
        }
    }
}
