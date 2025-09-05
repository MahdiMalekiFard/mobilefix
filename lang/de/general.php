<?php

declare(strict_types=1);

return [
    'submit'                         => 'Einreichen',
    'cancel'                         => 'Stornieren',
    'reset'                          => 'Zurücksetzen',
    'page'                           => [
        'index'  => [
            'page_title' => ':model Liste',
            'title'      => 'Alle :model Artikel',
            'desc'       => 'Alle verfügbar :model Artikel im System',
            'create'     => 'Neu :model',
        ],
        'create' => [
            'page_title' => 'Erstellen :model',
            'title'      => 'Neu registrieren :model',
            'desc'       => 'Bitte stellen Sie sicher, dass Sie die Genehmigung des Inhaltsmanagers haben, bevor Sie ein neues Element erstellen',
        ],
        'show'   => [
            'page_title' => ':model Details',
            'title'      => 'Details von :model',
            'desc'       => 'Alle Details von :model',
        ],
    ],
    'page_sections'                  => [
        'data' => 'Information',
    ],

    'are_you_shure_to_delete_record' => 'Möchten Sie diesen Datensatz wirklich löschen?',
    'currency'                       => 'EUR',
    'price_up_to'                    => 'Bis zu',
];
