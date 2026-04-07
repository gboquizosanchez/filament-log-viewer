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
            'filename' => [
                'label' => 'Filename',
            ],
        ],
        'actions' => [
            'view' => [
                'label' => 'View',
            ],
            'download' => [
                'label' => 'Download log :log',
                'bulk' => [
                    'label' => 'Download logs',
                    'error' => 'Error downloading the logs',
                ],
            ],
            'delete' => [
                'label' => 'Delete log :log',
                'success' => 'Log deleted successfully',
                'error' => 'Error deleting the log',
                'bulk' => [
                    'label' => 'Delete selected logs',
                ],
            ],
            'clear' => [
                'label' => 'Clear log :log',
                'success' => 'Log cleared successfully',
                'error' => 'Error clearing the log',
                'bulk' => [
                    'success' => 'Logs cleared successfully',
                    'label' => 'Clear selected logs',
                ],
            ],
            'close' => [
                'label' => 'Back',
            ],
        ],
        'modal' => [
            'search_placeholder' => 'Search logs...',
            'go_to_top' => 'Go to top',
            'go_to_bottom' => 'Go to bottom',
            'top' => 'Top',
            'bottom' => 'Bottom',
            'no_matching_entries' => 'No matching log entries.',
            'no_entries' => 'No log entries.',
            'context' => 'Context',
            'stack_trace' => 'Stack trace',
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
