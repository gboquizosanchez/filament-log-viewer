<?php

declare(strict_types=1);

return [

    /* -----------------------------------------------------------------
    | Driver
    | -----------------------------------------------------------------
    | Available drivers: 'daily', 'stack', 'raw'
    | -----------------------------------------------------------------
     */

    'driver' => env('FILAMENT_LOG_VIEWER_DRIVER', env('LOG_CHANNEL', 'stack')),

    /* -----------------------------------------------------------------
    | Resource configuration
    | -----------------------------------------------------------------
     */

    'resource' => [
        'slug' => 'logs',
        'cluster' => null,
    ],

    /* -----------------------------------------------------------------
    | Logs files can be cleared
    | -----------------------------------------------------------------
    */

    'clearable' => env('FILAMENT_LOG_VIEWER_CLEARABLE', false),

    /* -----------------------------------------------------------------
    |  Log files storage path
    | -----------------------------------------------------------------
     */

    'storage_path' => storage_path('logs'),

    /* -----------------------------------------------------------------
    |  Log files pattern
    | -----------------------------------------------------------------
     */

    'pattern' => [
        'prefix' => 'laravel-',
        'date' => '[0-9][0-9][0-9][0-9]-[0-9][0-9]-[0-9][0-9]',
        'extension' => '.log',
    ],

    /* -----------------------------------------------------------------
    |  Log entries per page
    | -----------------------------------------------------------------
    |  This defines how many logs and entries are displayed per page.
     */

    'per-page' => [
        5,
        10,
        25,
        30,
    ],

    /* -----------------------------------------------------------------
    |  Download settings
    | -----------------------------------------------------------------
     */

    'download' => [
        'prefix' => 'laravel-',

        'extension' => 'log',
    ],

    /* -----------------------------------------------------------------
    |  Icons
    | -----------------------------------------------------------------
     */

    'icons' => [
        'all' => 'heroicon-s-list-bullet',
        'emergency' => 'heroicon-s-bug-ant',
        'alert' => 'heroicon-s-megaphone',
        'critical' => 'heroicon-s-fire',
        'error' => 'heroicon-s-x-circle',
        'warning' => 'heroicon-s-exclamation-triangle',
        'notice' => 'heroicon-s-exclamation-circle',
        'info' => 'heroicon-s-information-circle',
        'debug' => 'heroicon-s-command-line',
    ],

    /* -----------------------------------------------------------------
    |  Colors
    | -----------------------------------------------------------------
     */

    'colors' => [
        'levels' => [
            'all' => '#8A8A8A',
            'emergency' => '#B71C1C',
            'alert' => '#D32F2F',
            'critical' => '#F44336',
            'error' => '#FF5722',
            'warning' => '#FF9100',
            'notice' => '#4CAF50',
            'info' => '#1976D2',
            'debug' => '#90CAF9',
        ],
    ],

    /* -----------------------------------------------------------------
    |  Strings to highlight in stack trace
    | -----------------------------------------------------------------
     */

    'highlight' => [
        '^#\d+', '^Stack trace:',
    ],
];
