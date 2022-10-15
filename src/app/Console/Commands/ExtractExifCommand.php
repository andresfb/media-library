<?php

namespace App\Console\Commands;

use App\Jobs\ExtractExifJob;
use App\Services\ExtractExifService;
use Illuminate\Console\Command;

class ExtractExifCommand extends Command
{
    protected $signature = 'extract:exif';

    protected $description = 'Extract and safe Exif data from all files';

    /**
     * handle Method.
     *
     * @return int
     */
    public function handle(): int
    {
        try {
            if ($this->confirm("Send job to Queue")) {
                ExtractExifJob::dispatch()
                    ->onQueue('media');

                $this->info("\nDone\n");
                return 0;
            }

            $howMany = (int) $this->ask("How many posts", 10);

            $service = resolve(ExtractExifService::class);
            $service->execute($howMany);

            if (!empty($service->messages)) {
                $this->newLine();
                foreach ($service->messages as $message) {
                    $this->warn($message);
                }
                $this->newLine();
            }

            $this->newLine();
            $this->info("Done");
            return 0;
        } catch (\Exception $e) {
            $this->newLine();
            $this->warn("Error found");
            $this->error($e->getMessage());
            $this->newLine();
            return 0;
        }
    }
}
