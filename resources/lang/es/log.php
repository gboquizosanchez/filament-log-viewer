<?php

declare(strict_types=1);

return [
    'dashboard' => [
        'title' => 'Visor de logs',
    ],
    'show' => [
        'title' => 'Ver el log del :log',
    ],
    'navigation' => [
        'group' => 'Logs',
        'label' => 'Visor de logs',
        'sort' => 100,
    ],
    'table' => [
        'columns' => [
            'date' => [
                'label' => 'Fecha',
            ],
            'level' => [
                'label' => 'Nivel',
            ],
            'message' => [
                'label' => 'Mensaje',
            ],
        ],
        'actions' => [
            'view' => [
                'label' => 'Ver',
            ],
            'download' => [
                'label' => 'Descargar el log del :log',
            ],
            'delete' => [
                'label' => 'Eliminar el log del :log',
                'success' => 'Log eliminado con éxito',
                'error' => 'Error al eliminar el log',
            ],
            'close' => [
                'label' => 'Volver',
            ],
        ],
        'detail' => [
            'title' => 'Detalle',
            'file_path' => 'Ruta del archivo',
            'log_entries' => 'Entradas',
            'size' => 'Tamaño',
            'created_at' => 'Creado el',
            'updated_at' => 'Actualizado el',
        ],
    ],
    'levels' => [
        'all' => 'Todos',
        'emergency' => 'Emergencia',
        'alert' => 'Alerta',
        'critical' => 'Crítico',
        'error' => 'Error',
        'warning' => 'Advertencia',
        'notice' => 'Aviso',
        'info' => 'Información',
        'debug' => 'Depuración',
    ],
];
