<?php

use App\Services\ImportContent\BibleImportService;
use App\Services\ImportContent\QuotesImportService;

return [

    BibleImportService::IMPORTER_KEY => [
        'file' => storage_path('app/upload/bibles.tsv'),
    ],

    QuotesImportService::IMPORTER_KEY => [
        'file' => storage_path('app/upload/quotes.tsv'),
    ],

];
