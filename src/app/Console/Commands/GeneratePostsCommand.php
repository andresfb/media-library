<?php

namespace App\Console\Commands;

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
            $howMany = (int) $this->ask("How many posts", 10);

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
