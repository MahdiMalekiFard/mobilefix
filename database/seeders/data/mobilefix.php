<?php

use App\Enums\CategoryTypeEnum;
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
];