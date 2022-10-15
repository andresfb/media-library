<?php

namespace App\Jobs;

use App\Services\ExtractExifService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ExtractExifJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private ExtractExifService $service;

    public function __construct(ExtractExifService $service = null)
    {
        $this->service = $service ?? resolve(ExtractExifService::class);;
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
            $this->service->execute();
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw $e;
        }
    }
}
