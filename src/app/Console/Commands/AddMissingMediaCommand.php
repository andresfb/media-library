<?php

namespace App\Console\Commands;

use App\Models\Item;
use Exception;
use Illuminate\Console\Command;

class AddMissingMediaCommand extends Command
{
    protected $signature = 'add:missing-media';

    protected $description = 'Looks for disabled Items and try to add the Media';

    /**
     * handle Method.
     *
     * @return int
     */
    public function handle(): int
    {
        try {
            $itemId = (int) $this->ask('Enter the Item Id', 0);
            if (!empty($itemId)) {
                $this->addMedia(Item::findOrFail($itemId));
            } else {
                $choice = $this->choice("Select a process", ['inactive', 'scan'], 0);
                if ($choice == 'inactive') {
                    $this->updateInactive();
                } else {
                    $this->scanItems();
                }
            }

            $this->newLine();
            $this->info("Done");
            return 0;
        } catch (Exception $e) {
            $this->newLine();
            $this->warn("Error found");
            $this->error($e->getMessage());
            $this->newLine();
            return 0;
        }
    }

    /**
     * updateInactive Method.
     *
     * @return void
     */
    private function updateInactive(): void
    {
        $items = Item::whereActive(false)->get();
        $this->newLine();

        if ($items->isEmpty()) {
            $this->info("No items with missing media found");
            $this->newLine();
            return;
        }

        foreach ($items as $item) {
            try {
                $this->addMedia($item);
            } catch (Exception $e) {
                $this->newLine();
                $this->error("Error on Item: $item->id " . $e->getMessage());
            }
        }

        $this->newLine();
    }

    /**
     * scanItems Method.
     *
     * @return void
     */
    private function scanItems(): void
    {
        Item::with('media')->chunk(5000, function ($items) {
            $this->newLine(2);
            $this->warn("Next 5000 records");
            $this->newLine();

            /** @var Item $item */
            foreach ($items as $item) {
                if ($item->hasMedia($item->type)) {
                    echo ".";
                    continue;
                }

                $this->newLine(2);
                $this->warn("No media found for $item->id");
                Item::disable($item->id);
                $this->warn('disabled');
                $this->newLine();
            }
        });

        $this->updateInactive();
    }

    /**
     * addMedia Method.
     *
     * @param Item $item
     * @return void
     * @throws Exception
     */
    private function addMedia(Item $item): void
    {
        $file = sprintf("%s%s/%s", config('raw-files.path'), $item->og_path, $item->og_file);
        if (!file_exists($file)) {
            $this->newLine();
            $this->error("File not found $file");
            $this->newLine();
            return;
        }

        $item->addMedia($file)
            ->preservingOriginal()
            ->toMediaCollection($item->type);

        $item->active = true;
        $item->save();

        echo ".";
    }
}
