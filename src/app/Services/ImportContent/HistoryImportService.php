<?php

namespace App\Services\ImportContent;

use App\Models\History;
use App\Traits\Messageble;
use Illuminate\Support\Facades\DB;
use Throwable;

class HistoryImportService implements ImportServiceInterface
{
    use Messageble;

    const IMPORTER_KEY = 'history';

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
        $this->updateHash();

        $this->setMessages("Finished importing history events");
    }

    /**
     * updateHash Method.
     *
     * @return void
     */
    private function updateHash(): void
    {
        $history = History::all();
        foreach ($history as $item) {
            $item->hash = md5($item->event_date->toDateString() . $item->event);
            $item->save();
            $this->progress();
        }
    }
}
