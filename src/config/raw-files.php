<?php

return [

    'path' => env('RAW_FILES_PATH'),

    'exclude' => env('EXCLUDE_RAW_FILES', '.'),

    'max_files' => env("MAX_RAW_IMPORT", 500),

];
