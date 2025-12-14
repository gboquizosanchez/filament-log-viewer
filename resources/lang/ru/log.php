<?php

declare(strict_types=1);

return [
    'dashboard' => [
        'title' => 'Просмотр логов',
    ],
    'show' => [
        'title' => 'Просмотр лога :log',
    ],
    'navigation' => [
        'group' => 'Логи',
        'label' => 'Просмотр логов',
        'sort' => 100,
    ],
    'table' => [
        'columns' => [
            'date' => [
                'label' => 'Дата',
            ],
            'level' => [
                'label' => 'Уровень',
            ],
            'message' => [
                'label' => 'Сообщение',
            ],
            'filename' => [
                'label' => 'Имя файла',
            ],
        ],
        'actions' => [
            'view' => [
                'label' => 'Просмотреть',
            ],
            'download' => [
                'label' => 'Скачать лог :log',
                'bulk' => [
                    'label' => 'Скачать логи',
                    'error' => 'Ошибка при скачивании логов',
                ],
            ],
            'delete' => [
                'label' => 'Удалить лог :log',
                'success' => 'Лог успешно удалён',
                'error' => 'Ошибка при удалении лога',
                'bulk' => [
                    'label' => 'Удалить выбранные логи',
                ],
            ],
            'clear' => [
                'label' => 'Очистить лог :log',
                'success' => 'Лог успешно очищен',
                'error' => 'Ошибка при очистке лога',
                'bulk' => [
                    'success' => 'Логи успешно очищены',
                    'label' => 'Очистить выбранные логи',
                ],
            ],
            'close' => [
                'label' => 'Назад',
            ],
        ],
        'detail' => [
            'title' => 'Детали',
            'file_path' => 'Путь к файлу',
            'log_entries' => 'Записи',
            'size' => 'Размер',
            'created_at' => 'Создан',
            'updated_at' => 'Обновлён',
        ],
    ],
    'levels' => [
        'all' => 'Все',
        'emergency' => 'Экстренный',
        'alert' => 'Тревога',
        'critical' => 'Критический',
        'error' => 'Ошибка',
        'warning' => 'Предупреждение',
        'notice' => 'Уведомление',
        'info' => 'Информация',
        'debug' => 'Отладка',
    ],
];
