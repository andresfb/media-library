<?php

namespace App\Services;

use App\Jobs\ConvertHeicToJpgJob;
use App\Models\Item;
use App\Traits\MediaAccessible;
use Exception;
use Illuminate\Support\Facades\Log;
use SplFileInfo;

class ImportMediaService
{
    use MediaAccessible;

    const HEIC_TYPE = 'heic';

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
     * @param string $path
     * @return void
     * @throws Exception
     */
    public function execute(int $howMany = 0, string $path = ""): void
    {
        Log::info("Media import started at " . now()->toDateTimeString());

        $this->maxFiles = empty($howMany)
            ? (int) config('raw-files.max_files')
            : $howMany;

        if (empty($path)) {
            $path = $this->getLatestImported();
        }

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
        if (app()->runningInConsole()) {
            echo $baseFolder . PHP_EOL;
        }

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
            $itemId = 0;

            try {
                $fileInfo = new SplFileInfo($file);
                $path = str_replace(config('raw-files.path'), '', $fileInfo->getPath());
                $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));

                try {
                    $imageSize = getimagesize($file);
                } catch (Exception) {
                    $imageSize = true;
                }

                $type = $extension == self::HEIC_TYPE || $imageSize ? "image" : "video";
                $ogItem = Item::select('id')
                    ->whereHash($hash)
                    ->first();

                $item = Item::updateOrCreate([
                    'hash' => $hash,
                    'og_path' => $path,
                ], [
                    'og_file' => $fileInfo->getFilename(),
                    'type' => $type,
                    'active' => true,
                    'og_item_id' => $ogItem?->id,
                ]);

                $itemId = $item->id;

                // If we get a file in HEIC format, send to a job to convert to JPG
                if ($extension == self::HEIC_TYPE) {
                    ConvertHeicToJpgJob::dispatch($itemId, $file)->onQueue('media');
                    $this->imported++;
                    continue;
                }

                $media = $item->addMedia($file)
                    ->preservingOriginal()
                    ->toMediaCollection($type);

                $path = $media->getPath();
                $this->updateAccess($path);

                $this->imported++;
            } catch (Exception $e) {
                $message = "$file got error: " . $e->getMessage();
                Log::error($message);

                Item::disable($itemId);
                if (app()->runningInConsole()) {
                    echo $message . PHP_EOL;
                }
            }
        }
    }
}
