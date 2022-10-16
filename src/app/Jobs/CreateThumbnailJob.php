<?php

namespace App\Jobs;

use App\Services\CreateThumbnailService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CreateThumbnailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private int $itemId;

    private CreateThumbnailService $service;

    public function __construct(int $itemId, CreateThumbnailService $service = null)
    {
        $this->itemId = $itemId;
        $this->service = $service ?? resolve(CreateThumbnailService::class);
    }

    /**
     * handle Method.
     *
     * @return void
     * @throws Exception
     */
    public function handle(): void
    {
        try {
            $this->service->execute($this->itemId);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw $e;
        }
    }
}
