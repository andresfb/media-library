<?php

namespace App\Console\Commands;

use App\Jobs\GenerateFeedJob;
use App\Services\AvatarGeneratorService;
use App\Services\GenerateFeedService;
use Exception;
use Illuminate\Console\Command;

class GenerateFeedCommand extends Command
{
    protected $signature = 'generate:feed';

    protected $description = 'Save the info of a given number of Posts into the Feed Mongo collection';


    /**
     * handle Method.
     *
     * @return int
     */
    public function handle(): int
    {
        try {
            if ($this->confirm("Send job to Queue")) {
                GenerateFeedJob::dispatch(
                    new GenerateFeedService(new AvatarGeneratorService())
                )->onQueue('default');

                $this->info("\nDone\n");
                return 0;
            }

            $howMany = (int) $this->ask("How many Posts", (int) config('posts.max_daily_feed'));

            $service = resolve(GenerateFeedService::class);
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
