<?php

namespace App\Jobs;

use App\Services\ImportMediaService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ImportMediaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private ImportMediaService $service;

    public function __construct(ImportMediaService $service)
    {
        $this->service = $service;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws Exception
     */
    public function handle(): void
    {
        try {
            $this->service->execute();
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw $e;
        }
    }
}
