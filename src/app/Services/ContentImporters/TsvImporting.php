<?php

namespace App\Services\ContentImporters;

use App\Traits\Messageble;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Throwable;

abstract class TsvImporting
{
    use Messageble;

    /**
     * importValues Method.
     *
     * @param Collection $values
     * @return void
     */
    abstract protected function importValues(Collection $values): void;

    /**
     * getKey Method.
     *
     * @return string
     */
    abstract protected function getKey(): string;


    /**
     * execute Method.
     *
     * @return void
     * @throws Throwable
     */
    public function execute(): void
    {
        $file = config("import." . $this->getKey() . ".file");
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

        $this->setMessages(sprintf("Imported %d items", $count - 1));
    }
}
