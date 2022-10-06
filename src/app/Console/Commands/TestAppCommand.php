<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use pcrov\JsonReader\JsonReader;

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

            $reader = new JsonReader();
            $reader->open("/data1/media-library-dev/storage/app/upload/reddit_jokes.json");

            $reader->read(); // Begin array
            $reader->read(); // First element, or end of array
            while($reader->type() === JsonReader::OBJECT) {
                $data = $reader->value();
                printf("%s: %s %s\n", $data["category"] ?? 'Reddit', $data["title"], $data["body"]);

                $reader->next();
            }

            $reader->close();

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
