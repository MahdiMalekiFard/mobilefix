<?php

return [
    'model'         => 'Contact Us',
    'permissions'   => [
        'view'   => 'View Contact Messages',
        'create' => 'Create Contact Messages',
        'update' => 'Update Contact Messages',
        'delete' => 'Delete Contact Messages',
    ],
    'exceptions'    => [
    ],
    'validations'   => [
    ],
    'enum'          => [
    ],
    'notifications' => [
        'new_message' => 'New contact message received',
        'message_replied' => 'Contact message has been replied to',
    ],
    'page'          => [
        'index' => [
            'title' => 'Contact Messages',
        ],
        'create' => [
            'title' => 'Create Contact Message',
        ],
        'edit' => [
            'title' => 'Edit Contact Message',
        ],
        'show' => [
            'title' => 'View Contact Message',
        ],
    ],
];
