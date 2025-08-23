<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Specification Cache Configuration
    |--------------------------------------------------------------------------
    |
    | Configure caching for specification results
    |
    */
    'cache' => [
        'enabled' => env('SPECIFICATION_CACHE_ENABLED', false),
        'ttl' => env('SPECIFICATION_CACHE_TTL', 3600),
        'prefix' => env('SPECIFICATION_CACHE_PREFIX', 'spec_'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Performance Configuration
    |--------------------------------------------------------------------------
    */
    'performance' => [
        'lazy_collections' => env('SPECIFICATION_USE_LAZY', true),
        'chunk_size' => env('SPECIFICATION_CHUNK_SIZE', 1000),
    ],
];
