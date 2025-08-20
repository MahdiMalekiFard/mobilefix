<?php

declare(strict_types=1);

return [
    'model'         => 'Page',
    'permissions'   => [
    ],
    'exceptions'    => [
    ],
    'validations'   => [
    ],
    'enum'          => [
        'type' => [
            'rules'            => 'Rules',
            'about-us'         => 'About Us',
            'terms-of_service' => 'Terms of Service',
            'privacy_policy'   => 'Privacy Policy',
        ],
    ],
    'notifications' => [
    ],
    'page'          => [
    ],

    'media'         => 'Media',
];
