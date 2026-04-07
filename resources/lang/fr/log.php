<?php

declare(strict_types=1);

return [
    'dashboard' => [
        'title' => 'Visionneuse de logs',
    ],
    'show' => [
        'title' => 'Voir le log :log',
    ],
    'navigation' => [
        'group' => 'Logs',
        'label' => 'Visionneuse de logs',
        'sort' => 100,
    ],
    'table' => [
        'columns' => [
            'date' => [
                'label' => 'Date',
            ],
            'level' => [
                'label' => 'Niveau',
            ],
            'message' => [
                'label' => 'Message',
            ],
            'filename' => [
                'label' => 'Nom du fichier',
            ],
        ],
        'actions' => [
            'view' => [
                'label' => 'Voir',
            ],
            'download' => [
                'label' => 'Télécharger le log :log',
                'bulk' => [
                    'label' => 'Télécharger sélectionnés',
                    'error' => 'Erreur lors du téléchargement des logs',
                ],
            ],
            'delete' => [
                'label' => 'Supprimer le log :log',
                'success' => 'Log supprimé avec succès',
                'error' => 'Erreur lors de la suppression du log',
                'bulk' => [
                    'label' => 'Supprimer les logs sélectionnés',
                ],
            ],
            'clear' => [
                'label' => 'Effacer le journal :log',
                'success' => 'Journal effacé avec succès',
                'error' => 'Erreur lors de l\'effacement du journal',
                'bulk' => [
                    'success' => 'Journaux effacés avec succès',
                    'label' => 'Effacer les journaux sélectionnés',
                ],
            ],
            'close' => [
                'label' => 'Retour',
            ],
        ],
        'modal' => [
            'search_placeholder' => 'Rechercher dans les logs...',
            'go_to_top' => 'Aller en haut',
            'go_to_bottom' => 'Aller en bas',
            'top' => 'Haut',
            'bottom' => 'Bas',
            'no_matching_entries' => 'Aucune entrée de journal correspondante.',
            'no_entries' => 'Aucune entrée de journal.',
            'context' => 'Contexte',
            'stack_trace' => 'Trace de la pile',
        ],
        'detail' => [
            'title' => 'Détail',
            'file_path' => 'Chemin du fichier',
            'log_entries' => 'Entrées',
            'size' => 'Taille',
            'created_at' => 'Créé le',
            'updated_at' => 'Mis à jour le',
        ],
    ],
    'levels' => [
        'all' => 'Tous',
        'emergency' => 'Urgent',
        'alert' => 'Alerte',
        'critical' => 'Critique',
        'error' => 'Erreur',
        'warning' => 'Avertissement',
        'notice' => 'Avis',
        'info' => 'Info',
        'debug' => 'Débogage',
    ],
];
