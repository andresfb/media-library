<?php

namespace App\Services;

use App\Models\Item;
use Exception;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;

class ConvertHeicToJpgService
{
    private string $tempFolder;


    public function __construct()
    {
        $this->tempFolder = config('raw-files.temp_convert_folder');
    }

    /**
     * execute Method.
     *
     * @param int $itemId
     * @param string $file
     * @return void
     * @throws Exception
     */
    public function execute(int $itemId, string $file): void
    {
        try {
            $item = Item::find($itemId);
            if (empty($item)) {
                return;
            }

            if (!file_exists($file)) {
                return;
            }

            $outFile = $this->tempFolder . md5(Str::random(24)) . ".jpg";

            Image::configure(['driver' => 'imagick']);
            Image::make($file)->save($outFile);

            $item->addMedia($outFile)->toMediaCollection('image');
        } catch (Exception $e) {
            Item::disable($itemId);
            throw $e;
        }
    }
}
