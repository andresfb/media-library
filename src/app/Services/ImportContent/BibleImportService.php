<?php

namespace App\Services\ImportContent;

use App\Models\Bible;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Throwable;

class BibleImportService implements ImportServiceInterface
{
    const IMPORTER_KEY = 'bible';

    private string $messages = "";

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

        $input = fopen($file, "r");
        $count = 0;

        while (!feof($input)) {
            $line = Str::of(fgets($input));
            $count++;

            // Skip the first line with the headers
            if ($count == 1) {
                continue;
            }

            $values = $line->replace(["\r", "\n", '"'], "")
                ->replace("&#8212;", "—")
                ->replace("\xD1", "—")
                ->replace(["<i>", "<I>", "</i>", "</I>"], "*")
                ->replace(["<b>", "<B>", "</b>", "</B>"], "**")
                ->trim()
                ->explode("\t");

            $this->importValues($values);
        }

        $this->messages = sprintf("Imported %d items", $count - 1);
    }

    /**
     * getMessages Method.
     *
     * @return string
     */
    public function getMessages(): string
    {
        return $this->messages;
    }


    /**
     * importValues Method.
     *
     * @param Collection $values
     * @return void
     */
    private function importValues(Collection $values): void
    {
        if (empty($values)) {
            return;
        }

        if (empty($values[0])) {
            return;
        }

        Bible::updateOrCreate([
            'verse' => $values[0],
        ],[
            'kjb' => $values[1] ?? '',
            'asv' => $values[2] ?? '',
            'drv' => $values[3] ?? '',
            'dbt' => $values[4] ?? '',
            'erv' => $values[5] ?? '',
            'wbt' => $values[6] ?? '',
            'web' => $values[7] ?? '',
            'ylt' => $values[8] ?? '',
            'akj' => $values[9] ?? '',
            'wnt' => $values[10] ?? '',
            'used' => false,
        ]);

        $this->progress();
    }

    /**
     * output Method.
     *
     * @return void
     */
    private function progress(): void
    {
        if (!app()->runningInConsole()) {
            return;
        }

        echo ".";
    }
}
