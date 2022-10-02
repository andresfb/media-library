<?php

namespace App\Console\Commands;

use App\Jobs\GeneratePostJob;
use App\Services\ContentOrchestratorService;
use App\Services\GeneratePostsService;
use Exception;
use Illuminate\Console\Command;

class GeneratePostsCommand extends Command
{
    /** @var string */
    protected $signature = 'generate:posts';

    /** @var string */
    protected $description = 'Generate a set of random Posts';

    /**
     * handle Method.
     *
     * @return int
     */
    public function handle(): int
    {
        try {
            if ($this->confirm("Send job to Queue", !config('app.debug'))) {
                GeneratePostJob::dispatch()
                    ->onQueue('ingestor');

                $this->info("\nDone\n");
                return 0;
            }

            $howMany = (int) $this->ask("How many posts", 10);

            $service = new GeneratePostsService(new ContentOrchestratorService());
            $service->execute($howMany);

            $this->info("\nDone\n");
            return 0;
        } catch (Exception $e) {
            $this->error("\nError found:\n");
            $this->error($e->getMessage());
            $this->info("");
            return 1;
        }
    }
}
