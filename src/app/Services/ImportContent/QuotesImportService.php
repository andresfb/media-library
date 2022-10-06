<?php

namespace App\Services\ImportContent;

use Illuminate\Support\Collection;

class QuotesImportService extends TsvImporting implements ImportServiceInterface
{
    const IMPORTER_KEY = 'quote';

    /** @inheritDoc */
    protected function importValues(Collection $values): void
    {
        if (empty($values)) {
            return;
        }

        if (empty($values[0])) {
            return;
        }
//
//        $first = (int) $values[0];
//        $i =

        $this->progress();
    }

    /** @inheritDoc */
    protected function getKey(): string
    {
        return self::IMPORTER_KEY;
    }
}
