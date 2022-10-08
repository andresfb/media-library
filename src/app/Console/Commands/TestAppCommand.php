<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class TestAppCommand extends Command
{
    /** @var string */
    protected $signature = 'test:app';

    /** @var string */
    protected $description = 'Command description';

    /**
     * handle Method.
     *
     * @return int
     */
    public function handle(): int
    {
        try {
            $this->info("Starting test");
            $this->newLine();

            $parts = Str::of(trim("Gwen Verdon, Actress and dancer (Cotton Club) in LA, CA"))->explode(",");
            $name = trim($parts->shift());
            $description = trim($parts->implode(","));

            dump($name);
            dump($description);

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
