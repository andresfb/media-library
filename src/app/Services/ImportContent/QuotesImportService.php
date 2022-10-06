<?php

namespace App\Services\ImportContent;

use App\Models\Quote;
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

        Quote::updateOrCreate([
            'hash' => md5($values[0])
        ],[
            'quote' => $values[0],
            'author' => $values[1],
            'category' => $values[2],
        ]);

        $this->progress();
    }

    /** @inheritDoc */
    protected function getKey(): string
    {
        return self::IMPORTER_KEY;
    }
}
