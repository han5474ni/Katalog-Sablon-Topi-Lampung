<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Vite Configuration
    |--------------------------------------------------------------------------
    |
    | Laravel will look for your manifest file to load the assets generated
    | by Vite. By default, the manifest file and assets live inside the
    | "public/build" directory, but you may change that here if needed.
    |
    */

    'manifest_path' => public_path('build/manifest.json'),

    'build_path' => public_path('build'),

    'hot_file' => public_path('hot'),

    'dev_server' => [
        'url' => env('VITE_DEV_SERVER_URL', 'http://localhost:5173'),
        'enabled' => env('VITE_DEV_SERVER', false),
    ],
];
