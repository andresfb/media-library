<?php

namespace App\Services\ImportContent;

use App\Models\Bible;
use App\Traits\Messageble;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Throwable;

class BibleImportService extends TsvImporting implements ImportServiceInterface
{
    const IMPORTER_KEY = 'bible';


    /** @inheritDoc */
    protected function importValues(Collection $values): void
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

    /** @inheritDoc */
    protected function getKey(): string
    {
        return self::IMPORTER_KEY;
    }
}
