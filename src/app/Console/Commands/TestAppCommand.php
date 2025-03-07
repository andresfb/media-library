<?php

namespace App\Console\Commands;

use App\Models\Item;
use App\Models\ItemFile;
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

            $itemFile = ItemFile::find(2);

            $disk = Storage::disk('s3');
            $signedUrl = $disk->temporaryUrl($itemFile->url, now()->addMinutes(30));

            dump($signedUrl);

            $this->info("\nDone\n");

            return 0;
        } catch (Exception $e) {
            $this->warn("\nError found:");
            $this->error($e->getMessage() . "\n");

            return 1;
        }
    }
}
