<?php

use App\Enums\CategoryTypeEnum;
use App\Enums\PaymentProviderEnum;
use App\Enums\SeoRobotsMetaEnum;

return [
    'blogs'      => [
        [
            'title'         => 'Schnelleinstieg in Laravel: Ein Leitfaden für Anfänger',
            'description'   => 'Eine praktische Einführung in Laravel für diejenigen, die ihre Projekte schnell mit diesem Framework starten möchten.',
            'body'          => 'Laravel ist eines der mächtigen und gleichzeitig einfachen PHP-Frameworks, das für die schnelle Entwicklung von Webanwendungen entwickelt wurde. In diesem Artikel lernen wir Schritt für Schritt, wie man Laravel installiert, das erste Projekt ausführt und einfache Seiten erstellt. Grundkonzepte wie Routing, Controller, Views und Models werden vorgestellt. Das Ziel ist es, ohne tiefgreifende Vorkenntnisse sehr schnell in die Laravel-Welt einzusteigen. Von der Composer-Installation bis zur Ausführung der ersten Seite mit Blade decken wir alles ab. Wenn Sie Anfänger sind, wird dieser Artikel ein ausgezeichneter Ausgangspunkt für Sie sein.',
            'slug'          => 'schnelleinstieg-in-laravel-ein-leitfaden-fuer-anfaenger',
            'published'     => true,
            'published_at'  => now(),
            'user_id'       => 2,
            'category_id'   => 1,
            'view_count'    => 2,
            'comment_count' => 1,
            'wish_count'    => 2,
            'languages'     => [
                'de',
            ],
            'seo_options'   => [
                'title'       => 'Schnelleinstieg in Laravel: Ein Leitfaden für Anfänger',
                'description' => 'Eine praktische Einführung in Laravel für diejenigen, die ihre Projekte schnell mit diesem Framework starten möchten.',
                'canonical'   => null,
                'old_url'     => null,
                'redirect_to' => null,
                'robots_meta' => SeoRobotsMetaEnum::NOINDEX_FOLLOW,
            ],
            'path'          => public_path('images/test/blogs/laravel.jpg'),
        ],
        [
            'title'         => 'Laravel-Architektur: Ein tiefer Einblick in die interne Struktur des Frameworks',
            'description'   => 'Wir untersuchen die MVC-Struktur, Service Container, Facades und andere Schlüsselkonzepte, um ein tieferes Verständnis von Laravel zu erlangen.',
            'body'          => '',
            'slug'          => 'laravel-architektur-ein-tiefer-einblick-in-die-interne-struktur-des-frameworks',
            'published'     => true,
            'published_at'  => now(),
            'user_id'       => 3,
            'category_id'   => 1,
            'view_count'    => 2,
            'comment_count' => 1,
            'wish_count'    => 2,
            'languages'     => [
                'de',
            ],
            'seo_options'   => [
                'title'       => 'Laravel-Architektur: Ein tiefer Einblick in die interne Struktur des Frameworks',
                'description' => 'Wir untersuchen die MVC-Struktur, Service Container, Facades und andere Schlüsselkonzepte, um ein tieferes Verständnis von Laravel zu erlangen.',
                'canonical'   => null,
                'old_url'     => null,
                'redirect_to' => null,
                'robots_meta' => SeoRobotsMetaEnum::NOINDEX_FOLLOW,
            ],
            'path'          => public_path('images/test/blogs/design.jpg'),
        ]
    ],

    'categories' => [
        [
            'title'       => 'Laravel',
            'slug'        => 'laravel',
            'description' => 'Laravel ist ein beliebtes und mächtiges Framework für die Webentwicklung mit der PHP-Sprache, das auf der MVC-Architektur (Model-View-Controller) basiert.',
            'body'        => 'Dieses Framework wurde mit dem Ziel entwickelt, die Entwicklung von Webanwendungen zu vereinfachen und bietet Funktionen wie einfaches Routing, Datenbankmanagement mit Eloquent ORM, Warteschlangensystem, Authentifizierung, E-Mail-Versand und die Blade-Template-Engine für Entwickler. Laravel macht mit seiner schönen Syntax und professionellen Tools die Entwicklung von kleinen bis großen Projekten schneller und angenehmer.',
            'seo_options' => [
                'title'       => 'Laravel',
                'description' => 'Laravel ist ein beliebtes und mächtiges Framework für die Webentwicklung mit der PHP-Sprache, das auf der MVC-Architektur (Model-View-Controller) basiert.',
                'canonical'   => null,
                'old_url'     => null,
                'redirect_to' => null,
                'robots_meta' => SeoRobotsMetaEnum::INDEX_FOLLOW,
            ],
            'type'        => CategoryTypeEnum::BLOG->value,
            'ordering'    => 1,
            'parent_id'   => null,
            'created_at'  => now(),
            'updated_at'  => now(),
            'languages'   => [
                'de',
            ],
            'path'        => public_path('images/test/categories/laravel-cat.png'),
        ],
    ] ,

    'brands' => [
        [
            'title' => 'Apple',
            'description' => 'Apple description',
            'slug' => 'apple',
            'ordering' => 1,
            'published' => true,
            'languages' => [
                'en',
            ],
            'seo_options' => [
                'title' => 'Apple',
                'description' => 'Apple description',
                'canonical' => null,
                'old_url' => null,
                'redirect_to' => null,
                'robots_meta' => SeoRobotsMetaEnum::INDEX_FOLLOW,
            ],
            'path' => public_path('assets/images/partner/apple.png'),
        ],
        [
            'title' => 'Samsung',
            'description' => 'Samsung description',
            'slug' => 'samsung',
            'ordering' => 2,
            'published' => true,
            'languages' => [
                'en',
            ],
            'seo_options' => [
                'title' => 'Samsung',
                'description' => 'Samsung description',
                'canonical' => null,
                'old_url' => null,
                'redirect_to' => null,
                'robots_meta' => SeoRobotsMetaEnum::INDEX_FOLLOW,
            ],
            'path' => public_path('assets/images/partner/samsung.png'),
        ]
    ],

    'devices' => [
        [
            'title' => 'iPhone 14 Pro',
            'brand_id' => 1,
            'description' => 'iPhone 14 Pro description',
            'slug' => 'iphone-14-pro',
            'ordering' => 1,
            'published' => true,
            'languages' => [
                'en',
            ],
        ],
        [
            'title' => 'iPhone 12 Pro',
            'brand_id' => 1,
            'description' => 'iPhone 12 Pro description',
            'slug' => 'iphone-12-pro',
            'ordering' => 2,
            'published' => true,
            'languages' => [
                'en',
            ],
        ],
        [
            'title' => 'Samsung Galaxy S23',
            'brand_id' => 2,
            'description' => 'Samsung Galaxy S23 description',
            'slug' => 'samsung-galaxy-s23',
            'ordering' => 3,
            'published' => true,
            'languages' => [
                'en',
            ],
        ],
        [
            'title' => 'Samsung Galaxy S22',
            'brand_id' => 2,
            'description' => 'Samsung Galaxy S22 description',
            'slug' => 'samsung-galaxy-s22',
            'ordering' => 4,
            'published' => true,
            'languages' => [
                'en',
            ],
        ]
    ],

    'payment_methods' => [
        [
            'title' => 'PayPal',
            'description' => 'PayPal description',
            'published' => true,
            'provider' => PaymentProviderEnum::PAYPAL->value,
            'languages' => [
                'en',
            ]
        ],
        [
            'title' => 'Stripe',
            'description' => 'Stripe description',
            'published' => true,
            'provider' => PaymentProviderEnum::STRIPE->value,
            'languages' => [
                'en',
            ]
        ],
    ],

    'problems' => [
        [
            'title' => 'Screen Replacement',
            'description' => 'The screen is cracked, broken, or unresponsive and needs to be replaced.',
            'published' => true,
            'ordering' => 1,
            'min_price' => 80.00,
            'max_price' => 250.00,
            'languages' => ['en'],
            'config' => [],
        ],
        [
            'title' => 'Battery Replacement',
            'description' => 'The battery drains quickly, does not charge, or the phone shuts down unexpectedly.',
            'published' => true,
            'ordering' => 2,
            'min_price' => 40.00,
            'max_price' => 120.00,
            'languages' => ['en'],
            'config' => [],
        ],
        [
            'title' => 'Charging Port Repair',
            'description' => 'The device does not charge or connect properly due to a faulty charging port.',
            'published' => true,
            'ordering' => 3,
            'min_price' => 35.00,
            'max_price' => 90.00,
            'languages' => ['en'],
            'config' => [],
        ],
        [
            'title' => 'Camera Repair',
            'description' => 'The camera is blurry, not working, or the lens is cracked.',
            'published' => true,
            'ordering' => 4,
            'min_price' => 50.00,
            'max_price' => 180.00,
            'languages' => ['en'],
            'config' => [],
        ],
        [
            'title' => 'Speaker/Microphone Repair',
            'description' => 'Problems with sound during calls, music playback, or voice recording.',
            'published' => true,
            'ordering' => 5,
            'min_price' => 30.00,
            'max_price' => 80.00,
            'languages' => ['en'],
            'config' => [],
        ],
        [
            'title' => 'Water Damage Repair',
            'description' => 'The phone has been exposed to water or other liquids and is malfunctioning.',
            'published' => true,
            'ordering' => 6,
            'min_price' => 60.00,
            'max_price' => 200.00,
            'languages' => ['en'],
            'config' => [],
        ],
        [
            'title' => 'Button Repair',
            'description' => 'Power, volume, or home buttons are stuck, unresponsive, or broken.',
            'published' => true,
            'ordering' => 7,
            'min_price' => 25.00,
            'max_price' => 70.00,
            'languages' => ['en'],
            'config' => [],
        ],
        [
            'title' => 'Back Glass Replacement',
            'description' => 'The back glass of the phone is cracked or shattered.',
            'published' => true,
            'ordering' => 8,
            'min_price' => 50.00,
            'max_price' => 150.00,
            'languages' => ['en'],
            'config' => [],
        ],
        [
            'title' => 'Software Troubleshooting',
            'description' => 'The phone is slow, freezing, or experiencing software glitches.',
            'published' => true,
            'ordering' => 9,
            'min_price' => 20.00,
            'max_price' => 60.00,
            'languages' => ['en'],
            'config' => [],
        ],
        [
            'title' => 'Data Recovery',
            'description' => 'Recover lost or deleted data from the device.',
            'published' => true,
            'ordering' => 10,
            'min_price' => 70.00,
            'max_price' => 200.00,
            'languages' => ['en'],
            'config' => [],
        ],
    ]
];