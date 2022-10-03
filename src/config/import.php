<?php

use App\Services\ImportContent\BibleImportService;

return [

    BibleImportService::IMPORTER_KEY => [
        'file' => storage_path('app/upload/bibles.tsv'),
    ],


];
