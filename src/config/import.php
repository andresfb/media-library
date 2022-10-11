<?php

use App\Services\ContentImporters\BibleImportService;
use App\Services\ContentImporters\BirthdaysImportService;
use App\Services\ContentImporters\DictionaryImportService;
use App\Services\ContentImporters\HistoryImportService;
use App\Services\ContentImporters\JokesImportService;
use App\Services\ContentImporters\QuotesImportService;
use App\Services\ContentImporters\QuranImportService;

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
