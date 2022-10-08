<?php

namespace App\Console\Commands;

use App\Models\Item;
use Illuminate\Console\Command;

class TestAppCommand extends Command
{
    /** @var string */
    protected $signature = 'test:app';

    /** @var string */
    protected $description = 'Command description';

    /**
     * handle Method.
     *
     * @return int
     */
    public function handle(): int
    {
        try {
            $this->info("Starting test");
            $this->newLine();

            $items = Item::select("items.*")
                ->with('media')
                ->get();

            foreach ($items as $item) {
                if (empty($item->media)) {
                    echo PHP_EOL . $item->id . PHP_EOL;
                }

                echo ".";
            }

            $this->newLine();
            $this->info("Done");
            return 0;
        } catch (\Exception $e) {
            $this->newLine();
            $this->warn("Error found");
            $this->error($e->getMessage());
            $this->newLine();
            return 0;
        }
    }
}
