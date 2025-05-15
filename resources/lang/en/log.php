<?php

declare(strict_types=1);

return [
    'dashboard' => [
        'title' => 'Log Viewer',
    ],
    'show' => [
        'title' => 'View log :log',
    ],
    'navigation' => [
        'group' => 'Logs',
        'label' => 'Log Viewer',
        'sort' => 100,
    ],
    'table' => [
        'columns' => [
            'date' => [
                'label' => 'Date',
            ],
            'level' => [
                'label' => 'Level',
            ],
            'message' => [
                'label' => 'Message',
            ],
        ],
        'actions' => [
            'view' => [
                'label' => 'View',
            ],
            'download' => [
                'label' => 'Download',
            ],
            'delete' => [
                'label' => 'Delete :record',
                'success' => 'Log deleted successfully',
                'error' => 'Error deleting the log',
            ],
            'close' => [
                'label' => 'Back',
            ],
        ],
        'detail' => [
            'title' => 'Detail',
            'file_path' => 'File Path',
            'log_entries' => 'Entries',
            'size' => 'Size',
            'created_at' => 'Created at',
            'updated_at' => 'Updated at',
        ],
    ],
    'levels' => [
        'all' => 'All',
        'emergency' => 'Emergency',
        'alert' => 'Alert',
        'critical' => 'Critical',
        'error' => 'Error',
        'warning' => 'Warning',
        'notice' => 'Notice',
        'info' => 'Info',
        'debug' => 'Debug',
    ],
];
