<?php

namespace App\Jobs;

use App\Services\ConvertHeicToJpgService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ConvertHeicToJpgJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private int $itemId;

    private string $file;

    private ConvertHeicToJpgService $service;


    public function __construct(int $itemId, string $file, ConvertHeicToJpgService $service = null)
    {
        $this->itemId = $itemId;
        $this->file = $file;
        if (empty($service)) {
            $this->service = resolve(ConvertHeicToJpgService::class);
        }
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
            $this->service->execute($this->itemId, $this->file);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw $e;
        }
    }
}
