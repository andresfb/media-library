<?php

namespace App\Services;

use App\Models\Item;
use Exception;
use FFMpeg\FFProbe;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Imagick;

class ExtractExifService
{
    private int $generated = 0;

    public array $messages = [];

    /**
     * execute Method.
     *
     * @param int $howMany
     * @return void
     */
    public function execute(int $howMany = 0): void
    {
        if (empty($howMany)) {
            $howMany = (int) config('raw-files.exif_max_records');
        }

        Log::info("Extracting the Exif of ($howMany) Items.");
        $items = Item::whereHasExif(false)
            ->whereActive(true)
            ->with('media')
            ->limit($howMany)
            ->orderBy('id')
            ->get();

        if ($items->isEmpty()) {
            $this->messages[] = "No items found missing exif data";
            Log::info(print_r($this->messages, true));
            return;
        }

        $items->map(function (Item $item) {
            $info = $this->getExit($item);
            $this->saveInfo($item, $info);
            if (app()->runningInConsole()) {
                echo ".";
            }
        });

        if (app()->runningInConsole()) {
            echo PHP_EOL;
        }

        Log::info("Extracted Exif of {$this->generated} Items");
    }


    /**
     * getExit Method.
     *
     * @param Item $item
     * @return array
     */
    private function getExit(Item $item): array
    {
        $file = !empty($item->media) && !$item->media->isEmpty()
            ? $item->media->first()->getPath()
            : sprintf("%s%s/%s", config('raw-files.path'), $item->og_path, $item->og_file);

        if (!file_exists($file)) {
            return [];
        }

        return $item->type == "image"
            ? $this->getImageExit($file)
            : $this->getVideoExif($file);
    }

    /**
     * getImageExit Method.
     *
     * @param string $file
     * @return array
     */
    private function getImageExit(string $file): array
    {
        $data = [];
        try {
            $image = new Imagick($file);
            $info = $image->identifyFormat('%[EXIF:*]');
            $data = $this->parseExif($info);

            $geom = $image->getImageGeometry();
            if (!empty($geom)) {
                $data['Width'] = $geom['width'] . "px";
                $data['Height'] = $geom['height'] . "px";
            }

            $resolution = $image->getImageResolution();
            if (!empty($resolution)) {
                $data["Resolution"] = sprintf("%sx%s", ceil($resolution['x']), ceil($resolution['y']));
            }

            $data['Features'] = $image->getFeatures();
            $data['Gravity'] = $image->getGravity();

            return $data;
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return $data;
        }
    }

    /**
     * getVideoExif Method.
     *
     * @param string $file
     * @return array
     */
    private function getVideoExif(string $file): array
    {
        $data = [];
        try {
            $prober = FFProbe::create();
            return $prober->format($file)->all();
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return $data;
        }
    }

    /**
     * parseExif Method.
     *
     * @param $info
     * @return array
     */
    private function parseExif($info): array
    {
        if (empty($info)) {
            return [];
        }

        return  Str::of($info)->explode("\n")->map(function (string $line) {
            return Str::of($line)
                ->replace("exif:", "")
                ->explode("=")
                ->toArray();
        })->map(function (array $item) {
            if (empty($item) || empty($item[0]) || empty($item[1])) {
                return [];
            }

            if ($item[0] == 'MakerNote') {
                return [];
            }

            return [$item[0] => $item[1]];
        })->reject(function ($item) {
            return empty($item);
        })->collapse()
        ->all();
    }

    /**
     * saveInfo Method.
     *
     * @param Item $item
     * @param array $info
     * @return void
     */
    private function saveInfo(Item $item, array $info): void
    {
        if (empty($info)) {
            return;
        }

        try {
            $item->exif = $info;
            $item->has_exif = true;
            $item->save();
            $this->generated++;
        } catch (Exception $e) {
            Log::error("Can't save exif for item id: $item->id " . $e->getMessage());
        }
    }
}
