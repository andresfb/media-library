<?php

namespace App\Console\Commands;

use App\Models\Item;
use Exception;
use Illuminate\Console\Command;

class TestAppCommand extends Command
{
    /** @var string */
    protected $signature = 'test:app';

    /** @var string */
    protected $description = 'Test commands runner';

    public function handle(): int
    {
        try {
            $this->info("Starting test\n");

//            $item = Item::find(1);
//            $image = $item->getFirstMedia('image')?->first();
//            if ($image === null) {
//                $this->error("No image found");
//            }
//
//            $file = $image->getPath();
//
//            $media = $item->addMedia($file)
//                ->preservingOriginal()
//                ->toMediaCollection('s3-image');
//
//            dump($media);

            $item = Item::find(2);
            $image = $item->getMedia('s3-image')->first();
            if ($image === null) {
                $this->error("No image found");
            }

            $url = $image->getUrl();
            $file = $image->getPath();

            dump($image->toArray(), $url, $file);


            $this->info("\nDone\n");

            return 0;
        } catch (Exception $e) {
            $this->warn("\nError found:");
            $this->error($e->getMessage() . "\n");

            return 1;
        }
    }
}
