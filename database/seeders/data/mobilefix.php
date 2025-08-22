<?php

declare(strict_types=1);

use App\Enums\CategoryTypeEnum;
use App\Enums\PageTypeEnum;
use App\Enums\PaymentProviderEnum;
use App\Enums\SeoRobotsMetaEnum;
use App\Enums\YesNoEnum;

return [
    'blogs'           => [
        [
            'title'         => 'Häufige Smartphone-Probleme und ihre Lösungen',
            'description'   => 'Erfahren Sie, welche Handyprobleme am häufigsten auftreten und wie sie schnell behoben werden können.',
            'body'          => '
<p>Smartphones sind aus unserem Alltag nicht mehr wegzudenken. Doch wie jedes technische Gerät sind auch sie anfällig für Defekte. Die häufigsten Probleme sind <strong>gesprungene Displays</strong>, <strong>schwache Akkus</strong> und <strong>Wasserschäden</strong>. Ein Displaybruch passiert oft durch Stürze, während Akkus mit der Zeit an Leistung verlieren. Wasserschäden entstehen häufig durch Unachtsamkeit im Alltag.</p>

<p>Zum Glück können diese Schäden in der Regel schnell behoben werden. Mit professionellen Ersatzteilen und erfahrenen Technikern wird Ihr Smartphone wieder wie neu. <em>Wichtig ist, Reparaturen nicht hinauszuzögern</em>, da sich kleine Probleme oft verschlimmern.</p>
',
            'slug'          => 'haeufige-smartphone-probleme-und-loesungen',
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
                'title'       => 'Häufige Smartphone-Probleme und ihre Lösungen',
                'description' => 'Erfahren Sie, welche Handyprobleme am häufigsten auftreten und wie sie schnell behoben werden können.',
                'canonical'   => null,
                'old_url'     => null,
                'redirect_to' => null,
                'robots_meta' => SeoRobotsMetaEnum::NOINDEX_FOLLOW,
            ],
            'path'          => public_path('assets/images/blog/01.jpg'),
            'tags'          => [
                'mobile', 'tablet', 'iPhone', 'iPad', 'Android',
            ],
        ],
        [
            'title'         => 'Warum eine Handyreparatur besser ist als ein Neukauf',
            'description'   => 'Entdecken Sie die Vorteile einer Reparatur gegenüber dem Kauf eines neuen Smartphones.',
            'body'          => '
<p>Viele Nutzer stehen vor der Frage: <strong>Reparatur oder Neukauf?</strong> Oft lohnt es sich, das Smartphone reparieren zu lassen. Zum einen sparen Sie bares Geld, da Reparaturen meist deutlich günstiger sind als ein neues Gerät. Zum anderen leisten Sie einen Beitrag zum <em>Umweltschutz</em>, indem Sie Elektroschrott vermeiden.</p>

<p>Eine Reparatur verlängert die Lebensdauer Ihres Geräts erheblich, besonders wenn Original- oder Premium-Ersatzteile verwendet werden. Zudem behalten Sie Ihre gespeicherten Daten und müssen sich nicht an ein neues System gewöhnen. Deshalb ist eine Reparatur häufig die nachhaltigere und wirtschaftlichere Wahl.</p>
',
            'slug'          => 'handyreparatur-besser-als-neukauf',
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
                'title'       => 'Warum eine Handyreparatur besser ist als ein Neukauf',
                'description' => 'Entdecken Sie die Vorteile einer Reparatur gegenüber dem Kauf eines neuen Smartphones.',
                'canonical'   => null,
                'old_url'     => null,
                'redirect_to' => null,
                'robots_meta' => SeoRobotsMetaEnum::NOINDEX_FOLLOW,
            ],
            'path'          => public_path('assets/images/blog/02.jpg'),
            'tags'          => [
                'iPad', 'Android',
            ],
        ],
        [
            'title'         => 'Tipps zur Pflege Ihres Smartphones für eine längere Lebensdauer',
            'description'   => 'Mit diesen einfachen Tipps bleibt Ihr Smartphone länger funktionsfähig und zuverlässig.',
            'body'          => '
<p>Ein Smartphone begleitet uns täglich – umso wichtiger ist es, es richtig zu pflegen. Verwenden Sie immer eine <strong>Schutzhülle</strong> und <strong>Panzerglas</strong>, um Displaybrüche zu vermeiden. Laden Sie den Akku nicht permanent bis 100 %, sondern zwischen 20 % und 80 %, um die Lebensdauer zu erhöhen.</p>

<p>Achten Sie darauf, Ihr Gerät nicht extremen Temperaturen auszusetzen, da Hitze und Kälte den Akku stark belasten können. Regelmäßige <em>Software-Updates</em> sorgen zudem für Sicherheit und bessere Leistung.</p>

<p>Mit diesen einfachen Maßnahmen sparen Sie nicht nur Reparaturkosten, sondern haben auch länger Freude an Ihrem Smartphone.</p>
',
            'slug'          => 'smartphone-pflege-tipps-laengere-lebensdauer',
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
                'title'       => 'Tipps zur Pflege Ihres Smartphones für eine längere Lebensdauer',
                'description' => 'Mit diesen einfachen Tipps bleibt Ihr Smartphone länger funktionsfähig und zuverlässig.',
                'canonical'   => null,
                'old_url'     => null,
                'redirect_to' => null,
                'robots_meta' => SeoRobotsMetaEnum::NOINDEX_FOLLOW,
            ],
            'path'          => public_path('assets/images/blog/03.jpg'),
            'tags'          => [
                'tablet', 'iPhone',
            ],
        ],
    ],

    'categories'      => [
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
        [
            'title'       => 'Faq Category',
            'slug'        => 'faq-category',
            'description' => 'Laravel ist ein beliebtes und mächtiges Framework für die Webentwicklung mit der PHP-Sprache, das auf der MVC-Architektur (Model-View-Controller) basiert.',
            'body'        => 'Dieses Framework wurde mit dem Ziel entwickelt, die Entwicklung von Webanwendungen zu vereinfachen und bietet Funktionen wie einfaches Routing, Datenbankmanagement mit Eloquent ORM, Warteschlangensystem, Authentifizierung, E-Mail-Versand und die Blade-Template-Engine für Entwickler. Laravel macht mit seiner schönen Syntax und professionellen Tools die Entwicklung von kleinen bis großen Projekten schneller und angenehmer.',
            'seo_options' => [
                'title'       => 'Faq Category',
                'description' => 'Laravel ist ein beliebtes und mächtiges Framework für die Webentwicklung mit der PHP-Sprache, das auf der MVC-Architektur (Model-View-Controller) basiert.',
                'canonical'   => null,
                'old_url'     => null,
                'redirect_to' => null,
                'robots_meta' => SeoRobotsMetaEnum::INDEX_FOLLOW,
            ],
            'type'        => CategoryTypeEnum::FAQ->value,
            'ordering'    => 2,
            'parent_id'   => null,
            'created_at'  => now(),
            'updated_at'  => now(),
            'languages'   => [
                'de',
            ],
            'path'        => public_path('images/test/categories/laravel-cat.png'),
        ],
    ],

    'brands'          => [
        [
            'title'       => 'Apple',
            'description' => 'Apple description',
            'slug'        => 'apple',
            'ordering'    => 1,
            'published'   => true,
            'languages'   => [
                'de',
            ],
            'seo_options' => [
                'title'       => 'Apple',
                'description' => 'Apple description',
                'canonical'   => null,
                'old_url'     => null,
                'redirect_to' => null,
                'robots_meta' => SeoRobotsMetaEnum::INDEX_FOLLOW,
            ],
            'path'        => public_path('assets/images/partner/iphone.png'),
        ],
        [
            'title'       => 'Samsung',
            'description' => 'Samsung description',
            'slug'        => 'samsung',
            'ordering'    => 2,
            'published'   => true,
            'languages'   => [
                'de',
            ],
            'seo_options' => [
                'title'       => 'Samsung',
                'description' => 'Samsung description',
                'canonical'   => null,
                'old_url'     => null,
                'redirect_to' => null,
                'robots_meta' => SeoRobotsMetaEnum::INDEX_FOLLOW,
            ],
            'path'        => public_path('assets/images/partner/samsung.png'),
        ],
        [
            'title'       => 'Oppo',
            'description' => 'Oppo description',
            'slug'        => 'oppo',
            'ordering'    => 3,
            'published'   => true,
            'languages'   => [
                'de',
            ],
            'seo_options' => [
                'title'       => 'Oppo',
                'description' => 'Oppo description',
                'canonical'   => null,
                'old_url'     => null,
                'redirect_to' => null,
                'robots_meta' => SeoRobotsMetaEnum::INDEX_FOLLOW,
            ],
            'path'        => public_path('assets/images/partner/oppo.png'),
        ],
        [
            'title'       => 'Vivo',
            'description' => 'Vivo description',
            'slug'        => 'vivo',
            'ordering'    => 4,
            'published'   => true,
            'languages'   => [
                'de',
            ],
            'seo_options' => [
                'title'       => 'Vivo',
                'description' => 'Vivo description',
                'canonical'   => null,
                'old_url'     => null,
                'redirect_to' => null,
                'robots_meta' => SeoRobotsMetaEnum::INDEX_FOLLOW,
            ],
            'path'        => public_path('assets/images/partner/vivo.png'),
        ],
        [
            'title'       => 'Nokia',
            'description' => 'Nokia description',
            'slug'        => 'nokia',
            'ordering'    => 5,
            'published'   => true,
            'languages'   => [
                'de',
            ],
            'seo_options' => [
                'title'       => 'Nokia',
                'description' => 'Nokia description',
                'canonical'   => null,
                'old_url'     => null,
                'redirect_to' => null,
                'robots_meta' => SeoRobotsMetaEnum::INDEX_FOLLOW,
            ],
            'path'        => public_path('assets/images/partner/nokia.png'),
        ],
        [
            'title'       => 'Huawei',
            'description' => 'Huawei description',
            'slug'        => 'huawei',
            'ordering'    => 6,
            'published'   => true,
            'languages'   => [
                'de',
            ],
            'seo_options' => [
                'title'       => 'Huawei',
                'description' => 'Huawei description',
                'canonical'   => null,
                'old_url'     => null,
                'redirect_to' => null,
                'robots_meta' => SeoRobotsMetaEnum::INDEX_FOLLOW,
            ],
            'path'        => public_path('assets/images/partner/huawei.png'),
        ],
        [
            'title'       => 'Xiaomi',
            'description' => 'Xiaomi description',
            'slug'        => 'xiaomi',
            'ordering'    => 7,
            'published'   => true,
            'languages'   => [
                'de',
            ],
            'seo_options' => [
                'title'       => 'Xiaomi',
                'description' => 'Xiaomi description',
                'canonical'   => null,
                'old_url'     => null,
                'redirect_to' => null,
                'robots_meta' => SeoRobotsMetaEnum::INDEX_FOLLOW,
            ],
            'path'        => public_path('assets/images/partner/xiaomi.png'),
        ],
    ],

    'devices'         => [
        [
            'title'       => 'iPhone 14 Pro',
            'brand_id'    => 1,
            'description' => 'iPhone 14 Pro description',
            'slug'        => 'iphone-14-pro',
            'ordering'    => 1,
            'published'   => true,
            'seo_options' => [
                'title'       => 'iPhone 14 Pro',
                'description' => 'iPhone 14 Pro description',
                'canonical'   => null,
                'old_url'     => null,
                'redirect_to' => null,
                'robots_meta' => SeoRobotsMetaEnum::INDEX_FOLLOW,
            ],
            'languages'   => [
                'de',
            ],
        ],
        [
            'title'       => 'iPhone 12 Pro',
            'brand_id'    => 1,
            'description' => 'iPhone 12 Pro description',
            'slug'        => 'iphone-12-pro',
            'ordering'    => 2,
            'published'   => true,
            'seo_options' => [
                'title'       => 'iPhone 12 Pro',
                'description' => 'iPhone 12 Pro description',
                'canonical'   => null,
                'old_url'     => null,
                'redirect_to' => null,
                'robots_meta' => SeoRobotsMetaEnum::NOINDEX_NOFOLLOW,
            ],
            'languages'   => [
                'de',
            ],
        ],
        [
            'title'       => 'Samsung Galaxy S23',
            'brand_id'    => 2,
            'description' => 'Samsung Galaxy S23 description',
            'slug'        => 'samsung-galaxy-s23',
            'ordering'    => 3,
            'published'   => true,
            'seo_options' => [
                'title'       => 'Samsung Galaxy S23',
                'description' => 'Samsung Galaxy S23 description',
                'canonical'   => null,
                'old_url'     => null,
                'redirect_to' => null,
                'robots_meta' => SeoRobotsMetaEnum::NOINDEX_NOFOLLOW,
            ],
            'languages'   => [
                'de',
            ],
        ],
        [
            'title'       => 'Samsung Galaxy S22',
            'brand_id'    => 2,
            'description' => 'Samsung Galaxy S22 description',
            'slug'        => 'samsung-galaxy-s22',
            'ordering'    => 4,
            'published'   => true,
            'seo_options' => [
                'title'       => 'Samsung Galaxy S22',
                'description' => 'Samsung Galaxy S22 description',
                'canonical'   => null,
                'old_url'     => null,
                'redirect_to' => null,
                'robots_meta' => SeoRobotsMetaEnum::NOINDEX_NOFOLLOW,
            ],
            'languages'   => [
                'de',
            ],
        ],
        [
            'title'       => 'OPPO Find X7 Ultra',
            'brand_id'    => 3,
            'description' => 'OPPO Find X7 Ultra description',
            'slug'        => 'oppo-find-x7-ultra',
            'ordering'    => 5,
            'published'   => true,
            'seo_options' => [
                'title'       => 'OPPO Find X7 Ultra',
                'description' => 'OPPO Find X7 Ultra description',
                'canonical'   => null,
                'old_url'     => null,
                'redirect_to' => null,
                'robots_meta' => SeoRobotsMetaEnum::NOINDEX_NOFOLLOW,
            ],
            'languages'   => [
                'de',
            ],
        ],
        [
            'title'       => 'OPPO Reno12 Pro',
            'brand_id'    => 3,
            'description' => 'OPPO Reno12 Pro description',
            'slug'        => 'oppo-reno12-pro',
            'ordering'    => 6,
            'published'   => true,
            'seo_options' => [
                'title'       => 'OPPO Reno12 Pro',
                'description' => 'OPPO Reno12 Pro description',
                'canonical'   => null,
                'old_url'     => null,
                'redirect_to' => null,
                'robots_meta' => SeoRobotsMetaEnum::NOINDEX_NOFOLLOW,
            ],
            'languages'   => [
                'de',
            ],
        ],
        [
            'title'       => 'Vivo X200 FE',
            'brand_id'    => 4,
            'description' => 'Vivo X200 FE description',
            'slug'        => 'vivo-x200-fe',
            'ordering'    => 7,
            'published'   => true,
            'seo_options' => [
                'title'       => 'Vivo X200 FE',
                'description' => 'Vivo X200 FE description',
                'canonical'   => null,
                'old_url'     => null,
                'redirect_to' => null,
                'robots_meta' => SeoRobotsMetaEnum::NOINDEX_NOFOLLOW,
            ],
            'languages'   => [
                'de',
            ],
        ],
        [
            'title'       => 'Vivo V50',
            'brand_id'    => 4,
            'description' => 'Vivo V50 description',
            'slug'        => 'vivo-v50',
            'ordering'    => 8,
            'published'   => true,
            'seo_options' => [
                'title'       => 'Vivo V50',
                'description' => 'Vivo V50 description',
                'canonical'   => null,
                'old_url'     => null,
                'redirect_to' => null,
                'robots_meta' => SeoRobotsMetaEnum::NOINDEX_NOFOLLOW,
            ],
            'languages'   => [
                'de',
            ],
        ],
        [
            'title'       => 'Nokia 3210',
            'brand_id'    => 5,
            'description' => 'Nokia 3210 description',
            'slug'        => 'nokia-3210',
            'ordering'    => 9,
            'published'   => true,
            'seo_options' => [
                'title'       => 'Nokia 3210',
                'description' => 'Nokia 3210 description',
                'canonical'   => null,
                'old_url'     => null,
                'redirect_to' => null,
                'robots_meta' => SeoRobotsMetaEnum::NOINDEX_NOFOLLOW,
            ],
            'languages'   => [
                'de',
            ],
        ],
        [
            'title'       => 'Nokia 6310',
            'brand_id'    => 5,
            'description' => 'Nokia 6310 description',
            'slug'        => 'nokia-6310',
            'ordering'    => 10,
            'published'   => true,
            'seo_options' => [
                'title'       => 'Nokia 6310',
                'description' => 'Nokia 6310 description',
                'canonical'   => null,
                'old_url'     => null,
                'redirect_to' => null,
                'robots_meta' => SeoRobotsMetaEnum::NOINDEX_NOFOLLOW,
            ],
            'languages'   => [
                'de',
            ],
        ],
        [
            'title'       => 'Huawei Mate 70',
            'brand_id'    => 6,
            'description' => 'Huawei Mate 70 description',
            'slug'        => 'huawei-mate-70',
            'ordering'    => 11,
            'published'   => true,
            'seo_options' => [
                'title'       => 'Huawei Mate 70',
                'description' => 'Huawei Mate 70 description',
                'canonical'   => null,
                'old_url'     => null,
                'redirect_to' => null,
                'robots_meta' => SeoRobotsMetaEnum::NOINDEX_NOFOLLOW,
            ],
            'languages'   => [
                'de',
            ],
        ],
        [
            'title'       => 'Huawei Pura 80 Ultra',
            'brand_id'    => 6,
            'description' => 'Huawei Pura 80 Ultra description',
            'slug'        => 'huawei-pura-80-ultra',
            'ordering'    => 12,
            'published'   => true,
            'seo_options' => [
                'title'       => 'Huawei Pura 80 Ultra',
                'description' => 'Huawei Pura 80 Ultra description',
                'canonical'   => null,
                'old_url'     => null,
                'redirect_to' => null,
                'robots_meta' => SeoRobotsMetaEnum::NOINDEX_NOFOLLOW,
            ],
            'languages'   => [
                'de',
            ],
        ],
        [
            'title'       => 'Xiaomi 15 Ultra',
            'brand_id'    => 7,
            'description' => 'Xiaomi 15 Ultra description',
            'slug'        => 'xiaomi-15-ultra',
            'ordering'    => 13,
            'published'   => true,
            'seo_options' => [
                'title'       => 'Xiaomi 15 Ultra',
                'description' => 'Xiaomi 15 Ultra description',
                'canonical'   => null,
                'old_url'     => null,
                'redirect_to' => null,
                'robots_meta' => SeoRobotsMetaEnum::NOINDEX_NOFOLLOW,
            ],
            'languages'   => [
                'de',
            ],
        ],
        [
            'title'       => 'Xiaomi 14T Pro',
            'brand_id'    => 7,
            'description' => 'Xiaomi 14T Pro description',
            'slug'        => 'xiaomi-14t-pro',
            'ordering'    => 14,
            'published'   => true,
            'seo_options' => [
                'title'       => 'Xiaomi 14T Pro',
                'description' => 'Xiaomi 14T Pro description',
                'canonical'   => null,
                'old_url'     => null,
                'redirect_to' => null,
                'robots_meta' => SeoRobotsMetaEnum::NOINDEX_NOFOLLOW,
            ],
            'languages'   => [
                'de',
            ],
        ],
    ],

    'payment_methods' => [
        [
            'title'       => 'PayPal',
            'description' => 'PayPal description',
            'published'   => true,
            'provider'    => PaymentProviderEnum::PAYPAL->value,
            'languages'   => [
                'de',
            ],
        ],
        [
            'title'       => 'Stripe',
            'description' => 'Stripe description',
            'published'   => true,
            'provider'    => PaymentProviderEnum::STRIPE->value,
            'languages'   => [
                'de',
            ],
        ],
    ],

    'problems'        => [
        [
            'title'       => 'Bildschirmaustausch',
            'description' => 'Der Bildschirm ist gesprungen, kaputt oder reagiert nicht und muss ersetzt werden.',
            'published'   => true,
            'ordering'    => 1,
            'min_price'   => 80.00,
            'max_price'   => 250.00,
            'languages'   => ['de'],
            'config'      => [],
        ],
        [
            'title'       => 'Batteriewechsel',
            'description' => 'Der Akku entlädt sich schnell, lädt nicht oder das Telefon schaltet sich unerwartet ab.',
            'published'   => true,
            'ordering'    => 2,
            'min_price'   => 40.00,
            'max_price'   => 120.00,
            'languages'   => ['de'],
            'config'      => [],
        ],
        [
            'title'       => 'Reparatur des Ladeanschlusses',
            'description' => 'Das Gerät lädt nicht oder stellt aufgrund eines fehlerhaften Ladeanschlusses keine ordnungsgemäße Verbindung her.',
            'published'   => true,
            'ordering'    => 3,
            'min_price'   => 35.00,
            'max_price'   => 90.00,
            'languages'   => ['de'],
            'config'      => [],
        ],
        [
            'title'       => 'Kamerareparatur',
            'description' => 'Die Kamera ist unscharf, funktioniert nicht oder das Objektiv ist gesprungen.',
            'published'   => true,
            'ordering'    => 4,
            'min_price'   => 50.00,
            'max_price'   => 180.00,
            'languages'   => ['de'],
            'config'      => [],
        ],
        [
            'title'       => 'Lautsprecher-/Mikrofonreparatur',
            'description' => 'Probleme mit dem Ton bei Anrufen, Musikwiedergabe oder Sprachaufzeichnung.',
            'published'   => true,
            'ordering'    => 5,
            'min_price'   => 30.00,
            'max_price'   => 80.00,
            'languages'   => ['de'],
            'config'      => [],
        ],
        [
            'title'       => 'Wasserschadensanierung',
            'description' => 'Das Telefon war Wasser oder anderen Flüssigkeiten ausgesetzt und funktioniert nicht richtig.',
            'published'   => true,
            'ordering'    => 6,
            'min_price'   => 60.00,
            'max_price'   => 200.00,
            'languages'   => ['de'],
            'config'      => [],
        ],
        [
            'title'       => 'Knopfreparatur',
            'description' => 'Die Ein-/Aus-Taste, die Lautstärke- oder die Home-Taste klemmen, reagieren nicht oder sind defekt.',
            'published'   => true,
            'ordering'    => 7,
            'min_price'   => 25.00,
            'max_price'   => 70.00,
            'languages'   => ['de'],
            'config'      => [],
        ],
        [
            'title'       => 'Austausch der Heckscheibe',
            'description' => 'Das Glas auf der Rückseite des Telefons ist gesprungen oder zersplittert.',
            'published'   => true,
            'ordering'    => 8,
            'min_price'   => 50.00,
            'max_price'   => 150.00,
            'languages'   => ['de'],
            'config'      => [],
        ],
        [
            'title'       => 'Software-Fehlerbehebung',
            'description' => 'Das Telefon ist langsam, friert ein oder weist Softwarefehler auf.',
            'published'   => true,
            'ordering'    => 9,
            'min_price'   => 20.00,
            'max_price'   => 60.00,
            'languages'   => ['de'],
            'config'      => [],
        ],
        [
            'title'       => 'Datenwiederherstellung',
            'description' => 'Stellen Sie verlorene oder gelöschte Daten vom Gerät wieder her.',
            'published'   => true,
            'ordering'    => 10,
            'min_price'   => 70.00,
            'max_price'   => 200.00,
            'languages'   => ['de'],
            'config'      => [],
        ],
    ],

    'sliders'         => [
        [
            'title'       => 'Schnelle & Zuverlässige Handyreparaturen',
            'description' => 'Lassen Sie Ihr Handy von zertifizierten Technikern in Deutschland reparieren. Schnelle Abwicklung, Originalteile und garantierte Zufriedenheit.',
            'published'   => true,
            'languages'   => [
                'de',
            ],
            'path'        => public_path('assets/images/slider/slider-1.jpg'),
        ],
        [
            'title'       => 'Preiswerte Reparaturen ohne Kompromisse',
            'description' => 'Hochwertige Handyreparaturen zu fairen Preisen. Transparente Kosten – keine versteckten Gebühren.',
            'published'   => true,
            'languages'   => [
                'de',
            ],
            'path'        => public_path('assets/images/slider/slider-2.jpg'),
        ],
        [
            'title'       => 'Service in ganz Deutschland',
            'description' => 'Ob in Berlin, München oder Hamburg – wir bieten professionelle Handyreparaturen, egal wo Sie sind.',
            'published'   => true,
            'languages'   => [
                'de',
            ],
            'path'        => public_path('assets/images/slider/slider-3.jpg'),
        ],
    ],

    'opinions'        => [
        [
            'user_name'    => 'Anna Müller',
            'company'      => 'Berlin Tech Store',
            'comment'      => 'Sehr professioneller Service! Mein iPhone war in weniger als einer Stunde repariert. Absolut empfehlenswert.',
            'ordering'     => 1,
            'view_count'   => 10,
            'published'    => true,
            'published_at' => now(),
            'path'         => public_path('assets/images/testimonial/01.jpg'),
        ],
        [
            'user_name'    => 'Markus Schneider',
            'company'      => 'Privatkunde',
            'comment'      => 'Transparente Preise und schnelle Reparatur. Endlich ein Reparaturservice, dem man vertrauen kann.',
            'ordering'     => 2,
            'view_count'   => 20,
            'published'    => true,
            'published_at' => now(),
            'path'         => public_path('assets/images/testimonial/02.jpg'),
        ],
        [
            'user_name'    => 'Laura Becker',
            'company'      => 'Mobile Solutions Hamburg',
            'comment'      => 'Wir lassen regelmäßig unsere Firmenhandys hier reparieren. Immer zuverlässig, schnell und freundlich.',
            'ordering'     => 3,
            'view_count'   => 30,
            'published'    => true,
            'published_at' => now(),
            'path'         => public_path('assets/images/testimonial/03.jpg'),
        ],
        [
            'user_name'    => 'Thomas Wagner',
            'company'      => 'Privatkunde',
            'comment'      => 'Mein Samsung-Display war kaputt. Die Reparatur war schnell, preiswert und die Qualität hervorragend.',
            'ordering'     => 4,
            'view_count'   => 40,
            'published'    => true,
            'published_at' => now(),
            'path'         => public_path('assets/images/testimonial/04.jpg'),
        ],
    ],

    'faqs'            => [
        [
            'title'        => 'Wie lange dauert eine Handyreparatur',
            'description'  => 'In den meisten Fällen dauert eine Reparatur zwischen 30 Minuten und 2 Stunden, abhängig vom Modell und Schaden. Für komplexere Reparaturen kann es 1–2 Werktage in Anspruch nehmen.',
            'ordering'     => 1,
            'category_id'  => 2,
            'favorite'     => YesNoEnum::YES->value,
            'published'    => true,
            'published_at' => now(),
            'languages'    => [
                'de',
            ],
        ],
        [
            'title'        => 'Werden Originalteile verwendet',
            'description'  => 'Ja, wir verwenden ausschließlich Originalteile oder Ersatzteile in höchster Qualität, um die Lebensdauer und Funktionalität Ihres Geräts zu gewährleisten.',
            'ordering'     => 2,
            'category_id'  => 2,
            'favorite'     => YesNoEnum::YES->value,
            'published'    => true,
            'published_at' => now(),
            'languages'    => [
                'de',
            ],
        ],
        [
            'title'        => 'Gibt es eine Garantie auf die Reparatur',
            'description'  => 'Auf jede Reparatur erhalten Sie eine Garantie von 6 bis 12 Monaten, je nach Art der Reparatur und verwendeten Ersatzteilen.',
            'ordering'     => 3,
            'category_id'  => 2,
            'favorite'     => YesNoEnum::YES->value,
            'published'    => true,
            'published_at' => now(),
            'languages'    => [
                'de',
            ],
        ],
        [
            'title'        => 'Muss ich einen Termin vereinbaren',
            'description'  => 'Sie können jederzeit ohne Termin vorbeikommen. Für eine schnellere Abwicklung empfehlen wir jedoch, online einen Termin zu buchen.',
            'ordering'     => 4,
            'category_id'  => 2,
            'favorite'     => YesNoEnum::NO->value,
            'published'    => true,
            'published_at' => now(),
            'languages'    => [
                'de',
            ],
        ],
        [
            'title'        => 'Welche Geräte können repariert werden',
            'description'  => 'Wir reparieren Smartphones aller gängigen Marken wie Apple, Samsung, Huawei, Xiaomi, Oppo, Nokia und viele mehr.',
            'ordering'     => 5,
            'category_id'  => 2,
            'favorite'     => YesNoEnum::NO->value,
            'published'    => true,
            'published_at' => now(),
            'languages'    => [
                'de',
            ],
        ],
    ],

    'pages'           => [
        [
            'title'       => 'Verlässliche Reparaturdienste in bester Qualität',
            'body'        => 'Mit einem Team erfahrener Techniker und modernster Ausstattung sorgen wir dafür, dass Ihr Smartphone in kürzester Zeit wieder einsatzbereit ist. Wir verwenden ausschließlich Original- und Premium-Ersatzteile, damit Ihr Gerät die gewohnte Leistung und Langlebigkeit behält.',
            'type'        => PageTypeEnum::ABOUT_US->value,
            'slug'        => 'wir-bieten-hochwertige-reparaturdienste',
            'view_count'  => 8,
            'languages'   => [
                'de',
            ],
            'seo_options' => [
                'title'       => 'Wir bieten hochwertige Reparaturdienste',
                'description' => 'Mit einem Team erfahrener Techniker und modernster Ausstattung sorgen wir dafür, dass Ihr Smartphone in kürzester Zeit wieder einsatzbereit ist.',
                'canonical'   => null,
                'old_url'     => null,
                'redirect_to' => null,
                'robots_meta' => SeoRobotsMetaEnum::INDEX_FOLLOW,
            ],
            'images'      => [
                public_path('assets/images/about/01.jpg'),
                public_path('assets/images/about/02.jpg'),
            ],
        ],
        [
            'title'       => 'Servicebedingungen',
            'body'        => '
<div class="terms-content">
<h3>Unsere Leistungen</h3>
<p>Sed ac sollicitudin ipsum. Vivamus vulputate, enim sit amet aliquet lacinia, ex mauris aliquam elit, vel pharetra augue arcu ultricies magna. Suspendisse justo erat, dignissim ut imperdiet ut, convallis vitae urna. Vivamus tincidunt lacinia metus sed suscipit. Phasellus luctus rhoncus mauris ut euismod. Aliquam elementum malesuada erat, vitae bibendum ex rutrum eget. Mauris sed nunc mauris. Curabitur semper sed justo a pellentesque. In hac habitasse platea dictumst. Mauris semper volutpat iaculis. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Curabitur consectetur dignissim nulla id ornare. Praesent placerat dolor vitae tellus lacinia, a malesuada est sodales. Praesent at consectetur sem, sed scelerisque arcu. Maecenas malesuada lorem id sagittis scelerisque. In hac habitasse platea dictumst.</p>
</div>

<div class="terms-content">
<h3>Cookies</h3>
<p>Pellentesque sit amet nulla facilisis, lobortis ex at, consequat diam. Pellentesque sed dui lorem. Aliquam vel euismod nunc. Nulla facilisi. Donec consectetur faucibus rutrum. Pellentesque ac ultricies sapien, ac iaculis erat. Vivamus posuere eget nulla sit amet vehicula. Donec finibus maximus eros, at tincidunt ipsum vestibulum ac. Integer vel metus vehicula, consequat velit a, eleifend mi. Curabitur erat mauris, luctus non dictum vel, fringilla dignissim quam. Phasellus eleifend porta fermentum. Pellentesque posuere massa vitae odio pulvinar feugiat. Fusce a risus sodales, maximus sapien sit amet, pharetra ipsum. Vivamus varius eros ac sapien pulvinar, nec tincidunt dui bibendum. Proin consectetur nibh tortor, nec vulputate ex posuere eget.</p>
</div>

<div class="terms-content">
<h3>Zahlungen</h3>
<p>Amet nulla facilisis, lobortis ex at, consequat diam. Pellentesque sed dui lorem. Aliquam vel euismod nunc. Nulla facilisi. Donec consectetur faucibus rutrum. Pellentesque ac ultricies sapien, ac iaculis erat. Vivamus posuere eget nulla sit amet vehicula. Donec finibus maximus eros, at tincidunt ipsum vestibulum ac. Integer vel metus vehicula, consequat velit a, eleifend mi. Curabitur erat mauris, luctus non dictum vel, fringilla dignissim quam. Phasellus eleifend porta fermentum. Pellentesque posuere massa vitae odio pulvinar feugiat. Fusce a risus sodales, maximus sapien sit amet, pharetra ipsum. Vivamus varius eros ac sapien pulvinar, nec tincidunt dui bibendum. Proin consectetur nibh tortor, nec vulputate ex posuere eget.</p>
</div>

<div class="terms-content">
<h3>Rückerstattungsrichtlinie</h3>
<p>Donec ut vestibulum sem, in faucibus mauris. Nulla et luctus nulla. Vestibulum consectetur nisi nec lobortis pretium. Fusce dignissim in sem in bibendum. Vivamus fermentum massa et egestas gravida. Suspendisse at vulputate ante, id tempus nunc. Curabitur sed dolor a elit ornare commodo. Curabitur blandit enim nulla, ornare suscipit risus pretium ut. Nullam rhoncus, sem eget dapibus elementum, purus dolor rutrum magna, nec laoreet odio sapien sit amet erat.</p>
<p>Proin non ante purus. Donec ante enim, semper vel mauris at, rutrum blandit mauris. Vivamus at ante sit amet leo consequat viverra quis at odio. Proin arcu magna, placerat sed lorem id, rutrum convallis ante.</p>
<p>Nam venenatis vestibulum mauris ut viverra. Ut porta consequat lorem a ullamcorper. In et arcu quam. Nunc tristique justo nec lectus ornare placerat. Nulla ut fringilla mi. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae.</p>
</div>

<div class="terms-content">
<h3>Verlinkung auf unsere Inhalte</h3>
<p>Sed ac sollicitudin ipsum. Vivamus vulputate enim sit amet aliquet lacinia mauris aliquam elit:</p>
<ul class="terms-list ms-4">
<li>1. Ut scelerisque hendrerit venenatis</li>
<li>2. Proin fermentum lacus nec augue blandit placerat</li>
<li>3. Ut vestibulum elit justo suscipit sem ultricies</li>
<li>4. Integer fermentum vitae magna in condimentum</li>
<li>5. Aenean ultrices neque id pellentesque tincidunt</li>
<li>6. Donec ut vestibulum sem, in faucibus mauris.</li>
</ul>

<p><strong>Stand:</strong> Januar 2025</p>
</div>',
            'type'        => PageTypeEnum::TERMS_OF_SERVICE->value,
            'slug'        => 'Servicebedingungen',
            'view_count'  => 18,
            'languages'   => [
                'de',
            ],
            'seo_options' => [
                'title'       => 'Servicebedingungen',
                'description' => 'Sed ac sollicitudin ipsum. Vivamus vulputate, enim sit amet aliquet lacinia, ex mauris aliquam elit, vel pharetra augue arcu ultricies magna.',
                'canonical'   => null,
                'old_url'     => null,
                'redirect_to' => null,
                'robots_meta' => SeoRobotsMetaEnum::INDEX_FOLLOW,
            ],
        ],
        [
            'title'       => 'Datenschutzrichtlinie',
            'body'        => '
<div class="terms-content">
<h3>Datenschutzrichtlinie</h3>
<p>Aenean ullamcorper est est, ac bibendum ipsum tincidunt vehicula. Nulla faucibus vulputate lorem, vitae placerat felis blandit ut. Nam sem quam, euismod sit amet augue et, mollis congue nisi. Vestibulum fringilla lobortis nunc ac tincidunt. Cras nec convallis quam. Maecenas non sem ut enim facilisis rhoncus. Sed odio ex, efficitur ac commodo sed, convallis vitae lectus. Aenean at urna ac tellus ullamcorper pretium. Aliquam erat volutpat. Aliquam sit amet tellus in tortor posuere convallis quis nec tellus. Nulla eu mauris sit amet enim eleifend congue. Quisque aliquam, turpis quis elementum tempus, velit arcu dignissim dui, a vehicula lectus nisi non felis.</p>
</div>

<div class="terms-content">
<h3>Erhebung von Informationen</h3>
<p>Donec ac pulvinar diam, ac mollis augue. Etiam interdum fringilla magna, at placerat libero malesuada sed. Proin tincidunt a sapien at facilisis. Cras nec lectus pretium, convallis tellus eu, placerat augue. Curabitur luctus odio efficitur elit volutpat, quis venenatis tellus vestibulum. Nam ultrices massa id tellus commodo, at mollis elit mattis. Etiam eget ultrices lectus, at faucibus mauris. Integer at mauris ex. Vivamus interdum cursus mi quis venenatis. Sed pulvinar efficitur quam quis congue. Ut vel ornare lorem. Vivamus mi mi, vestibulum nec eleifend eu, lobortis ac neque. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed hendrerit augue dui, non rutrum enim ultrices vel. Fusce mattis ullamcorper nisl, sit amet venenatis odio tincidunt eget.</p>
</div>

<div class="terms-content">
<h3>Nutzung der Informationen</h3>
<p>Phasellus commodo venenatis erat, et vestibulum mi fringilla in. Proin elit urna, condimentum ut elit id, imperdiet rutrum orci. Praesent vehicula velit at est rutrum lacinia. Nullam accumsan at tortor in ullamcorper. Proin semper sagittis nisl, vitae finibus nisl maximus non. Cras dictum risus quis augue tempor egestas. Proin luctus fermentum nunc, eget pretium dolor tristique id.</p>
<p>Suspendisse hendrerit ex sit amet augue lobortis ullamcorper. Maecenas dignissim facilisis orci, non imperdiet sapien ornare at. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.</p>
<p>Nam ultrices mi mauris, eget tempus massa ornare id. Aenean rhoncus vestibulum diam, ut dapibus dolor vehicula non. Proin rhoncus convallis commodo.</p>
</div>

<div class="terms-content">
<h3>Sicherheit der Benutzerdaten</h3>
<p>Integer justo neque imperdiet vitae consequat in vehicula quis dolor orbi lorem leo volutpat a tristique :</p>
<ul class="terms-list ms-4">
<li>1. Ut scelerisque hendrerit venenatis</li>
<li>2. Proin fermentum lacus nec augue blandit placerat</li>
<li>3. Ut vestibulum elit justo suscipit sem ultricies</li>
<li>4. Integer fermentum vitae magna in condimentum</li>
<li>5. Aenean ultrices neque id pellentesque tincidunt</li>
<li>6. Donec ut vestibulum sem, in faucibus mauris.</li>
</ul>
</div>

<div class="terms-content">
<h3>Urheberrecht und Sicherheit</h3>
<p>Vestibulum bibendum metus quis purus sagittis ultricies. Vestibulum fringilla urna volutpat eros pharetra consectetur. Integer rutrum eu odio et pulvinar. Sed hendrerit pellentesque faucibus. In venenatis lacus sit amet vehicula efficitur. Suspendisse pulvinar malesuada dui non mollis. Aliquam urna massa, rutrum vel luctus in, facilisis a turpis. Ut aliquet accumsan turpis, eget egestas sem pellentesque nec. Phasellus faucibus congue tempor. Mauris ac massa scelerisque metus pulvinar feugiat in ut leo. Proin congue felis orci. Suspendisse consectetur nisl at faucibus venenatis. Quisque pretium rhoncus dui, porttitor varius mi iaculis nec.</p>
</div>',
            'type'        => PageTypeEnum::PRIVACY_POLICY->value,
            'slug'        => 'Datenschutzrichtlinie',
            'view_count'  => 6,
            'languages'   => [
                'de',
            ],
            'seo_options' => [
                'title'       => 'Datenschutzrichtlinie',
                'description' => 'Aenean ullamcorper est est, ac bibendum ipsum tincidunt vehicula. Nulla faucibus vulputate lorem, vitae placerat felis blandit ut.',
                'canonical'   => null,
                'old_url'     => null,
                'redirect_to' => null,
                'robots_meta' => SeoRobotsMetaEnum::INDEX_FOLLOW,
            ],
        ],
    ],

    'services'        => [
        [
            'title'       => 'Displayreparatur',
            'description' => 'Professionelle Reparatur von gesprungenen oder defekten Smartphone-Displays.',
            'body'        => '
<p>Ein kaputtes Display ist eines der häufigsten Probleme bei Smartphones. Ob Risse, Streifen im Bild oder ein komplett schwarzer Bildschirm – wir bieten eine schnelle und zuverlässige <strong>Displayreparatur</strong> für alle gängigen Modelle. Dabei verwenden wir ausschließlich <em>Original- oder Premium-Ersatzteile</em>, um beste Qualität zu gewährleisten.</p>
',
            'slug'        => 'displayreparatur',
            'published'   => true,
            'languages'   => [
                'de',
            ],
            'seo_options' => [
                'title'       => 'Displayreparatur',
                'description' => 'Professionelle Reparatur von gesprungenen oder defekten Smartphone-Displays.',
                'canonical'   => null,
                'old_url'     => null,
                'redirect_to' => null,
                'robots_meta' => SeoRobotsMetaEnum::NOINDEX_FOLLOW,
            ],
            'path'        => public_path('assets/images/service/01.jpg'),
        ],
        [
            'title'       => 'Akkutausch',
            'description' => 'Austausch schwacher oder defekter Smartphone-Akkus für längere Laufzeit.',
            'body'        => '
<p>Mit der Zeit verliert jeder Akku an Leistung. Ihr Smartphone hält nicht mehr den ganzen Tag durch? Wir führen einen professionellen <strong>Akkutausch</strong> durch, damit Ihr Gerät wieder die volle Energie hat. Unsere Techniker verwenden geprüfte Ersatzakkus, die für eine lange Lebensdauer sorgen.</p>
',
            'slug'        => 'akkutausch',
            'published'   => true,
            'languages'   => [
                'de',
            ],
            'seo_options' => [
                'title'       => 'Akkutausch',
                'description' => 'Austausch schwacher oder defekter Smartphone-Akkus für längere Laufzeit.',
                'canonical'   => null,
                'old_url'     => null,
                'redirect_to' => null,
                'robots_meta' => SeoRobotsMetaEnum::NOINDEX_FOLLOW,
            ],
            'path'        => public_path('assets/images/service/02.jpg'),
        ],
        [
            'title'       => 'Wasserschaden-Reparatur',
            'description' => 'Schnelle Hilfe bei Flüssigkeitsschäden an Ihrem Smartphone.',
            'body'        => '
<p>Ein verschüttetes Getränk oder ein Sturz ins Wasser kann Ihr Smartphone schwer beschädigen. Unser Team ist spezialisiert auf die <strong>Wasserschaden-Reparatur</strong> und rettet viele Geräte erfolgreich. Je schneller Sie handeln, desto höher sind die Chancen, dass Ihr Handy wieder voll funktionsfähig wird.</p>
',
            'slug'        => 'wasserschaden-reparatur',
            'published'   => true,
            'languages'   => [
                'de',
            ],
            'seo_options' => [
                'title'       => 'Wasserschaden-Reparatur',
                'description' => 'Schnelle Hilfe bei Flüssigkeitsschäden an Ihrem Smartphone.',
                'canonical'   => null,
                'old_url'     => null,
                'redirect_to' => null,
                'robots_meta' => SeoRobotsMetaEnum::NOINDEX_FOLLOW,
            ],
            'path'        => public_path('assets/images/service/03.jpg'),
        ],
        [
            'title'       => 'Ladebuchse-Reparatur',
            'description' => 'Reparatur oder Austausch defekter Ladeanschlüsse für zuverlässiges Laden.',
            'body'        => '
<p>Ihr Handy lädt nicht mehr richtig oder das Kabel wackelt? Dann könnte die Ladebuchse defekt sein. Wir übernehmen die <strong>Ladebuchse-Reparatur</strong> oder tauschen diese professionell aus, damit Ihr Smartphone wieder problemlos geladen werden kann.</p>
',
            'slug'        => 'ladebuchse-reparatur',
            'published'   => true,
            'languages'   => [
                'de',
            ],
            'seo_options' => [
                'title'       => 'Ladebuchse-Reparatur',
                'description' => 'Reparatur oder Austausch defekter Ladeanschlüsse für zuverlässiges Laden.',
                'canonical'   => null,
                'old_url'     => null,
                'redirect_to' => null,
                'robots_meta' => SeoRobotsMetaEnum::NOINDEX_FOLLOW,
            ],
            'path'        => public_path('assets/images/service/04.jpg'),
        ],
        [
            'title'       => 'Kamera-Reparatur',
            'description' => 'Reparatur von Front- und Rückkameras für gestochen scharfe Bilder.',
            'body'        => '
<p>Die Smartphone-Kamera ist für viele unverzichtbar. Wenn die Kamera verschwommen ist, nicht mehr fokussiert oder komplett ausfällt, bieten wir eine schnelle <strong>Kamera-Reparatur</strong>. So können Sie wieder Fotos und Videos in gewohnter Qualität aufnehmen.</p>
',
            'slug'        => 'kamera-reparatur',
            'published'   => true,
            'languages'   => [
                'de',
            ],
            'seo_options' => [
                'title'       => 'Kamera-Reparatur',
                'description' => 'Reparatur von Front- und Rückkameras für gestochen scharfe Bilder.',
                'canonical'   => null,
                'old_url'     => null,
                'redirect_to' => null,
                'robots_meta' => SeoRobotsMetaEnum::NOINDEX_FOLLOW,
            ],
            'path'        => public_path('assets/images/service/05.jpg'),
        ],
        [
            'title'       => 'Software-Fehlerbehebung',
            'description' => 'Behebung von Systemproblemen, Viren und Softwarefehlern.',
            'body'        => '
<p>Nicht jeder Defekt ist hardwarebedingt. Auch Softwareprobleme wie Abstürze, Viren oder Update-Fehler können die Nutzung einschränken. Wir bieten eine gründliche <strong>Software-Fehlerbehebung</strong>, damit Ihr Smartphone wieder flüssig und sicher läuft.</p>
',
            'slug'        => 'software-fehlerbehebung',
            'published'   => true,
            'languages'   => [
                'de',
            ],
            'seo_options' => [
                'title'       => 'Software-Fehlerbehebung',
                'description' => 'Behebung von Systemproblemen, Viren und Softwarefehlern.',
                'canonical'   => null,
                'old_url'     => null,
                'redirect_to' => null,
                'robots_meta' => SeoRobotsMetaEnum::NOINDEX_FOLLOW,
            ],
            'path'        => public_path('assets/images/service/06.jpg'),
        ],
    ],
];
