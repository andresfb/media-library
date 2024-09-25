<?php

return [

    'path' => env('RAW_FILES_PATH'),

    'exclude' => env('EXCLUDE_RAW_FILES', ['.', '#']),

    'max_files' => env('MAX_RAW_IMPORT', 500),

    'exif_max_records' => env('MAX_EXIF_RECORDS', 500),

    'temp_convert_folder' => env('TEMP_CONVERT_FOLDER'),
];
