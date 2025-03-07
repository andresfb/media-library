<?php

namespace App\Jobs;

use App\Services\MoveToMinioService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class MoveToMinioJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(private int $itemId)
    {
    }

    /**
     * @throws Exception
     */
    public function handle(MoveToMinioService $service): void
    {
        try {
            $service->execute($this->itemId);
        } catch (Exception $e) {
            Log::error("Error moving item id: $this->itemId to Minio: {$e->getMessage()}");

            throw $e;
        }
    }
}
