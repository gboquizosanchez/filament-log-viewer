<?php

declare(strict_types=1);

return [
    'dashboard' => [
        'title' => 'Visualizador de logs',
    ],
    'show' => [
        'title' => 'Ver o log :log',
    ],
    'navigation' => [
        'group' => 'Logs',
        'label' => 'Visualizador de logs',
        'sort' => 100,
    ],
    'table' => [
        'columns' => [
            'date' => [
                'label' => 'Data',
            ],
            'level' => [
                'label' => 'Nível',
            ],
            'message' => [
                'label' => 'Mensagem',
            ],
        ],
        'actions' => [
            'view' => [
                'label' => 'Ver',
            ],
            'download' => [
                'label' => 'Baixar',
            ],
            'delete' => [
                'label' => 'Excluir :record',
                'success' => 'Log excluído com sucesso',
                'error' => 'Erro ao excluir o log',
            ],
            'close' => [
                'label' => 'Voltar',
            ],
        ],
        'detail' => [
            'title' => 'Detalhes',
            'file_path' => 'Caminho do arquivo',
            'log_entries' => 'Entradas',
            'size' => 'Tamanho',
            'created_at' => 'Criado em',
            'updated_at' => 'Atualizado em',
        ],
    ],
];
