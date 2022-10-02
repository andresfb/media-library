<?php

namespace App\Jobs;

use App\Models\Post;
use App\Services\GeneratePostsService;
use App\Services\ImportMediaService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GeneratePostJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var GeneratePostsService */
    private GeneratePostsService $service;


    public function __construct(GeneratePostsService $service)
    {
        $this->service = $service;
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
