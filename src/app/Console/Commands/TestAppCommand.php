<?php

namespace App\Console\Commands;

use App\Models\Item;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

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

            $item = Item::find(10);
            $image = $item->getMedia('image')->first();
            if ($image === null) {
                $this->error("No image found");
            }

            $file = "/" . $image->getPathRelativeToRoot();
            $file_info = pathinfo($file);

            Storage::disk('s3')->put(
                $file,
                Storage::disk('media')->get($file)
            );

            $url = Storage::disk('s3')->url($file);

            dump($image->toArray(), $url, $file_info);

            $this->info("\nDone\n");

            return 0;
        } catch (Exception $e) {
            $this->warn("\nError found:");
            $this->error($e->getMessage() . "\n");

            return 1;
        }
    }
}
