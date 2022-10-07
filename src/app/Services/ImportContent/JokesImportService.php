<?php

namespace App\Services\ImportContent;

use App\Models\Joke;
use App\Traits\Messageble;
use pcrov\JsonReader\Exception;
use pcrov\JsonReader\InputStream\IOException;
use pcrov\JsonReader\InvalidArgumentException;
use pcrov\JsonReader\JsonReader;
use RuntimeException;

class JokesImportService implements ImportServiceInterface
{
    use Messageble;

    const IMPORTER_KEY = 'joke';

    public function execute()
    {
        $files = config("import." . self::IMPORTER_KEY . ".files");
        if (empty($files)) {
            throw new RuntimeException("No files found to import");
        }

        foreach ($files as $file) {
            try {
                if (!file_exists($file)) {
                    throw new RuntimeException("$file not found");
                }

                if (app()->runningInConsole()) {
                    echo PHP_EOL . PHP_EOL . "Importing $file" . PHP_EOL . PHP_EOL;
                }

                $this->importFile($file);
            } catch (Exception $e) {
                $this->setMessages($e->getMessage());
            }
        }
    }

    /**
     * importFile Method.
     *
     * @param string $file
     * @return void
     * @throws Exception
     * @throws IOException
     * @throws InvalidArgumentException
     */
    private function importFile(string $file): void
    {
        $reader = new JsonReader();
        $reader->open($file);
        $fileName = basename($file);

        $reader->read(); // Begin array
        $reader->read(); // First element, or end of array
        while($reader->type() === JsonReader::OBJECT) {
            $data = $reader->value();

            if (empty(trim($data['body']))) {
                $reader->next();
                continue;
            }

            Joke::updateOrCreate([
                'hash' => md5($fileName . $data['id']),
            ],[
                'category' => $data["category"] ?? 'Reddit',
                'title' => $data["title"] ?? 'Stupid Stuff',
                'body' => $data["body"],
                'used' => false,
            ]);

            $this->progress();
            $reader->next();
        }

        $reader->close();
    }
}
