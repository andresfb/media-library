<?php

namespace App\Console\Commands;

use App\Jobs\MoveToMinioJob;
use App\Models\Item;
use App\Services\MoveToMinioService;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class MoveToMinioCommand extends Command
{
    private bool $toScreen = false;

    private bool $dispatchJob = false;

    private ?MoveToMinioService $service = null;

    protected $signature = 'move:to-minio {screen?}';

    protected $description = 'Move all the media files to Minio';

    public function handle(): int
    {
        $this->toScreen = !blank($this->argument('screen'));

        try {
            if ($this->toScreen) {
                $this->info("Loading Items...\n");
            }

            $items = Item::select('id')
                ->pendingMove()
                ->get();

            if ($items->isEmpty()) {
                if ($this->toScreen) {
                    $this->error("No items to move.\n");
                }

                return 0;
            }

            if ($this->toScreen) {
                $this->dispatchJob = $this->confirm("Send Job to queue");
            }

            $items->each(function (Item $item) {
                $this->processItem($item);
            });

            if ($this->toScreen) {
                $this->info("\nDone\n");
            }

            return 0;
        } catch (Exception $e) {
            Log::error($e);

            if ($this->toScreen) {
                $this->warn("\nError found:");
                $this->error($e->getMessage() . "\n");
            }

            return 1;
        }
    }

    private function processItem(Item $item): void
    {
        if (!$this->dispatchJob) {
            try {
                $this->service()->execute($item->id);
            } catch (Exception $e) {
                $this->error("Error moving item id: $item->id to Minio: {$e->getMessage()}\n");
            }

            dd('adios');

            return;
        }

        MoveToMinioJob::dispatch($item->id)
            ->onQueue('minio-move')
            ->delay(now()->addSeconds(5));
    }

    private function service(): MoveToMinioService
    {
        if ($this->service === null) {
            $this->service = app(MoveToMinioService::class)
                ->setToScreen($this->toScreen);
        }

        return $this->service;
    }
}
