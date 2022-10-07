<?php

namespace App\Console\Commands;

use App\Services\ImportContent\ImportContentOrchestratorService;
use Illuminate\Console\Command;

class ImportContentCommand extends Command
{
    /** @var string */
    protected $signature = 'import:content';

    /** @var string */
    protected $description = 'Imports the databases to use as source for the Post titles and text';

    private ImportContentOrchestratorService $service;


    public function __construct(ImportContentOrchestratorService $service)
    {
        parent::__construct();
        $this->service = $service;
    }

    /**
     * handle Method.
     *
     * @return int
     */
    public function handle(): int
    {
        try {
            $option = $this->choice(
                "Select data to import",
                $this->service->getOptionKeys(),
                0
            );

            $importer = $this->service->getInstance($option);
            $importer->execute();

            $this->newLine(2);
            $this->info($importer->getMessages());

            $this->newLine();
            $this->info("Done");
            $this->newLine();

            return 0;
        } catch (\Exception $e) {
            $this->newLine();
            $this->warn("Error found:");
            $this->error($e->getMessage());
            $this->newLine();
            return 1;
        }
    }
}
