<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Foundation\Testing\WithFaker;

class TestCommand extends Command
{
    use WithFaker;

    /** @var string */
    protected $signature = 'test';

    /** @var string */
    protected $description = 'Command to run some tests';

    /**
     * handle Method.
     *
     * @return int
     */
    public function handle(): int
    {
        try {
            $this->info("\nStarting test");

            $this->info("\nDone");
            return 0;
        } catch (Exception $e) {
            $this->error("\nError found:\n");
            $this->error($e->getMessage());
            $this->info("");
            return 1;
        }
    }
}
