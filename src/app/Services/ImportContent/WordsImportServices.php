<?php

namespace App\Services\ImportContent;

use App\Models\Word;
use App\Traits\Messageble;
use Illuminate\Support\Facades\DB;

class WordsImportServices implements ImportServiceInterface
{
    use Messageble;

    const IMPORTER_KEY = 'words';

    public function execute()
    {
        DB::table('wn_synset')->orderBy('synset_id')->chunk(5000, function ($words) {
            if (app()->runningInConsole()) {
                echo "\n\nNext chunk of 5000\n\n";
            }

            foreach ($words as $word) {
                $definition = DB::table('wn_gloss')->where('synset_id', $word->synset_id)->first();
                if (empty($definition)) {
                    continue;
                }

                Word::updateOrCreate([
                    'hash' => md5($word->synset_id.$word->w_num.$word->word),
                ],[
                    'word' => $word->word,
                    'definition' => $definition->gloss,
                    'used' => 0,
                ]);

                $this->progress();
            }
        });

        DB::unprepared("TRUNCATE `wn_gloss`;TRUNCATE `wn_synset`");
    }
}
