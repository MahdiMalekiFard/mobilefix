<?php

return [
    'model'         => 'تماس با ما',
    'permissions'   => [
        'view'   => 'مشاهده پیام‌های تماس',
        'create' => 'ایجاد پیام تماس',
        'update' => 'ویرایش پیام تماس',
        'delete' => 'حذف پیام تماس',
    ],
    'exceptions'    => [
    ],
    'validations'   => [
    ],
    'enum'          => [
    ],
    'notifications' => [
        'new_message' => 'پیام تماس جدید دریافت شد',
        'message_replied' => 'به پیام تماس پاسخ داده شد',
    ],
    'page'          => [
        'index' => [
            'title' => 'پیام‌های تماس',
        ],
        'create' => [
            'title' => 'ایجاد پیام تماس',
        ],
        'edit' => [
            'title' => 'ویرایش پیام تماس',
        ],
        'show' => [
            'title' => 'مشاهده پیام تماس',
        ],
    ],
];
