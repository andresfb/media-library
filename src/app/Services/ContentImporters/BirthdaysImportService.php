<?php

namespace App\Services\ContentImporters;

use App\Models\Birthday;
use App\Traits\Messageble;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

class BirthdaysImportService implements ImportServiceInterface
{
    use Messageble;

    const IMPORTER_KEY = 'birthday';

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
        $this->updateFields();

        $this->setMessages("Finished importing famous birthdays");
    }

    /**
     * updateFields Method.
     *
     * @return void
     */
    private function updateFields(): void
    {
        $bdays = Birthday::all();
        foreach ($bdays as $bday) {
            $parts = Str::of(trim($bday->name))->explode(",");
            $name = trim($parts->shift());
            $description = trim($parts->implode(","));

            $bday->hash = md5($bday->birthday->toDateString() . $name);
            $bday->name = $name;
            $bday->description = $description;
            $bday->save();

            $this->progress();
        }
    }
}
