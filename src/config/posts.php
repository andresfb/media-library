<?php

return [

    'search_url' => env('SEARCH_URL', 'https://duckduckgo.com/?q='),

    'max_random_posts' => env('MAX_RANDOM_POSTS', 1000),

    'max_content_reuse' => env('MAX_CONTENT_REUSE', 3),

    'max_daily_feed' => env('MAX_DAILY_FEED', 500),

    'cache_posts' => (bool) env('USE_POSTS_CACHE', false),

    'per_page' => (int) env('POSTS_PER_PAGE', 20),

];
