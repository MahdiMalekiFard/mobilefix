<?php

return [
    'model'         => 'مدل',
    'permissions'   => [
    ],
    'exceptions'    => [
        'published_at_after_now' => 'تاریخ انتشار می بایست تاریخی بعد از الان باشد',
    ],
    'validations'   => [
    ],
    'enum'          => [
    ],
    'notifications' => [
    ],
    'page'          => [
    ],
    'help'          => [
        'published_at_explanation' => 'تاریخ و زمان آینده‌ای را تنظیم کنید که این وبلاگ باید منتشر شود.',
        'will_publish_immediately' => 'این وبلاگ بلافاصله پس از ذخیره منتشر خواهد شد.',
    ],
];
