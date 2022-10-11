<?php

namespace App\Services\ContentImporters;

use App\Models\Quran;
use App\Traits\Messageble;
use pcrov\JsonReader\JsonReader;
use Throwable;

class QuranImportService implements ImportServiceInterface
{
    use Messageble;

    const IMPORTER_KEY = 'quran';

    /**
     * execute Method.
     *
     * @return void
     * @throws Throwable
     */
    public function execute(): void
    {
        $file = config("import." . self::IMPORTER_KEY . ".file");
        throw_if(!file_exists($file));

        $reader = new JsonReader();
        $reader->open($file);

        $reader->read(); // Begin array
        $reader->read(); // First element, or end of array
        while($reader->type() === JsonReader::OBJECT) {
            $data = $reader->value();
            $this->importData($data);
            $reader->next();
        }

        $reader->close();
        $this->setMessages("Finished importimg quran verses");
    }

    /**
     * importData Method.
     *
     * @param array $data
     * @return void
     */
    private function importData(array $data): void
    {
        foreach ($data['Chapter'] as $chapter) {

            foreach ($chapter['Verse'] as $item) {
                Quran::updateOrCreate([
                    'hash' => md5($chapter['_ChapterID'] . $item['_VerseID'] . $item['__cdata']),
                ], [
                    'chapter_id' => (int) $chapter['_ChapterID'],
                    'chapter_name' => $chapter['_ChapterName'],
                    'verse_id' => (int) $item['_VerseID'],
                    'verse' => $item['__cdata'],
                ]);

                $this->progress();
            }
        }
    }
}
