<?php

declare(strict_types=1);

return [
    'dashboard' => [
        'title' => 'Visualizzatore di log',
    ],
    'show' => [
        'title' => 'Vedi il log :log',
    ],
    'navigation' => [
        'group' => 'Log',
        'label' => 'Visualizzatore di log',
        'sort' => 100,
    ],
    'table' => [
        'columns' => [
            'date' => [
                'label' => 'Data',
            ],
            'level' => [
                'label' => 'Livello',
            ],
            'message' => [
                'label' => 'Messaggio',
            ],
        ],
        'actions' => [
            'view' => [
                'label' => 'Vedi',
            ],
            'download' => [
                'label' => 'Scarica',
            ],
            'delete' => [
                'label' => 'Elimina :record',
                'success' => 'Log eliminato con successo',
                'error' => 'Errore durante l\'eliminazione del log',
            ],
            'close' => [
                'label' => 'Indietro',
            ],
        ],
        'detail' => [
            'title' => 'Dettaglio',
            'file_path' => 'Percorso del file',
            'log_entries' => 'Voci',
            'size' => 'Dimensione',
            'created_at' => 'Creato il',
            'updated_at' => 'Aggiornato il',
        ],
    ],
];
