<?php

namespace App\Console\Commands;

use App\Jobs\ImportMediaJob;
use App\Services\ImportMediaService;
use Exception;
use Illuminate\Console\Command;

class ImportMediaCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:media';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports the media files read from the raw storage';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        try {
            if ($this->confirm("Send job to Queue")) {
                ImportMediaJob::dispatch()
                    ->onQueue('ingestor');

                $this->info("\nDone\n");
                return 0;
            }

            $howMany = (int) $this->ask("How many", config('raw-files.max_files'));
            $job = new ImportMediaService();
            $job->execute($howMany);

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
