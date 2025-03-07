<?php

namespace App\Console\Commands;

use App\Models\Item;
use Exception;
use Illuminate\Console\Command;

class MoveToMinioCommand extends Command
{
    protected $signature = 'move:to-minio';

    protected $description = 'Move all the media fils to Minio';

    public function handle(): int
    {
        try {
            $this->info("Starting move...\n");

            $items = Item::select('id')
                ->pendingMove()
                ->get();

            if ($items->isEmpty()) {
                $this->error("No items to move.\n");

                return 0;
            }

            $items->each(function (Item $item) {

            });

            $this->info("\nDone\n");

            return 0;
        } catch (Exception $e) {
            $this->warn("\nError found:");
            $this->error($e->getMessage() . "\n");

            return 1;
        }
    }
}
