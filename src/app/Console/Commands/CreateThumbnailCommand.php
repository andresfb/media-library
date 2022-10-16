<?php

namespace App\Console\Commands;

use App\Jobs\CreateThumbnailJob;
use App\Models\Item;
use App\Services\CreateThumbnailService;
use Exception;
use Illuminate\Console\Command;

class CreateThumbnailCommand extends Command
{
    private int $processed = 0;

    protected $signature = 'create:thumbnail';

    protected $description = 'Create a thumbnail from a given Item of video type';

    private CreateThumbnailService $service;


    public function __construct(CreateThumbnailService $service)
    {
        parent::__construct();
        $this->service = $service;
    }

    /**
     * handle Method.
     *
     * @return int
     */
    public function handle(): int
    {
        try {
            $itemId = (int) $this->ask('Enter the Item Id', 0);
            if (empty($itemId)) {
                $howMany = (int) $this->ask("How many items to scan?", 10);
                if (empty($howMany)) {
                    throw new Exception("Invalid entry");
                }

                $this->scanItems($howMany);
            } else {
                $this->service->execute($itemId);
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
     * scanItems Method.
     *
     * @param int $howMany
     * @return void
     * @throws Exception
     */
    private function scanItems(int $howMany): void
    {
        $job = $this->confirm("Send job to Queue");
        $this->newLine();

        $this->warn("Loading items");
        $this->newLine();

        $pending = $this->loadPending($howMany);
        if (empty($pending)) {
            throw new Exception("No pending items found");
        }

        foreach ($pending as $itemId) {
            if ($job) {
                echo "-";
                CreateThumbnailJob::dispatch($itemId)
                    ->onQueue('media')
                    ->delay(now()->addSeconds(15));
            } else {
                echo ".";
                $this->service->execute($itemId);
            }

            $this->processed++;
        }

        $this->newLine();
        $this->warn("\nProcessed {$this->processed} Items");
        $this->newLine();
    }

    /**
     * loadPending Method.
     *
     * @param int $howMany
     * @return array
     */
    private function loadPending(int $howMany): array
    {
        $count = 0;
        $pending = [];
        $toScan = $howMany < 100
            ? 500
            : ($howMany < 1000
                ? $howMany * 5
                : $howMany * 2);

        while ($count < $howMany) {
            $items = Item::whereType('video')
                ->whereActive(true)
                ->with('media')
                ->limit($toScan)
                ->get();

            if ($items->isEmpty()) {
                break;
            }

            foreach ($items as $item) {
                if ($item->hasMedia('thumb')) {
                    continue;
                }

                $pending[] = $item->id;
                if (count($pending) >= $howMany) {
                    break;
                }
            }

            $count += count($pending);
        }

        return $pending;
    }
}
