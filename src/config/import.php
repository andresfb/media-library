<?php

use App\Services\ImportContent\BibleImportService;
use App\Services\ImportContent\JokesImportService;
use App\Services\ImportContent\QuotesImportService;

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
    ]
];
