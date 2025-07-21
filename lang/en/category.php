<?php

return [
    'model'         => 'Category',
    'permissions'   => [
    ],
    'exceptions'    => [
        'not_allowed_to_delete_category_due_to_blogs' => 'This category is used in blogs and cannot be deleted',
        'not_allowed_to_delete_category_due_to_faqs'  => 'This category is used in faqs and cannot be deleted',
    ],
    'validations'   => [
    ],
    'enum'          => [
        'type' => [
            'blog'      => 'Blog',
            'portfolio' => 'Portfolio',
            'faq'       => 'Faq',
        ]
    ],
    'notifications' => [
    ],
    'page'          => [
    ],
];
