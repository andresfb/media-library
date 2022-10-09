<?php

namespace App\Console\Commands;

use App\Jobs\GeneratePostJob;
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
            if ($this->confirm("Send job to Queue")) {
                GeneratePostJob::dispatch()->onQueue('default');

                $this->info("\nDone\n");
                return 0;
            }

            $howMany = (int) $this->ask("How many posts", 1000);

            $service = resolve(GeneratePostsService::class);
            $service->execute($howMany);

            $this->info("\nDone\n");
            return 0;
        } catch (Exception $e) {
            $this->newLine();
            $this->warn("Error found:");
            $this->error($e->getMessage());
            $this->newLine();
            return 1;
        }
    }
}
