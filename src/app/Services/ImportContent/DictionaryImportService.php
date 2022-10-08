<?php

namespace App\Services\ImportContent;

use App\Traits\Messageble;
use Illuminate\Support\Facades\DB;
use Throwable;

class DictionaryImportService implements ImportServiceInterface
{
    use Messageble;

    const IMPORTER_KEY = 'dictionary';

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

        DB::unprepared(file_get_contents($file));

        $this->setMessages("Finished importing dictionary records");
    }
}
