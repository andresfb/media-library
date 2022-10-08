<?php

use App\Services\ImportContent\BibleImportService;
use App\Services\ImportContent\BirthdaysImportService;
use App\Services\ImportContent\DictionaryImportService;
use App\Services\ImportContent\HistoryImportService;
use App\Services\ImportContent\JokesImportService;
use App\Services\ImportContent\QuotesImportService;
use App\Services\ImportContent\QuranImportService;

return [

    BibleImportService::IMPORTER_KEY => [
        'file' => storage_path('app/upload/bibles.tsv'),
    ],

    QuotesImportService::IMPORTER_KEY => [
        'file' => storage_path('app/upload/quotes.tsv'),
    ],

    JokesImportService::IMPORTER_KEY => [
        'files' => [
            storage_path('app/upload/reddit_jokes.json'),
            storage_path('app/upload/stupidstuff.json'),
            storage_path('app/upload/wocka.json'),
        ]
    ],

    QuranImportService::IMPORTER_KEY => [
        'file' => storage_path('app/upload/quran-english-pickthall.json'),
    ],

    HistoryImportService::IMPORTER_KEY => [
        'file' => storage_path('app/upload/todayinhistory.sql'),
    ],

    BirthdaysImportService::IMPORTER_KEY => [
        'file' => storage_path('app/upload/famousbirthdays.sql'),
    ],

    DictionaryImportService::IMPORTER_KEY => [
        'file' => storage_path('app/upload/wordnet20-from-prolog-all-3.sql'),
    ],
];
