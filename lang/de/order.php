<?php

declare(strict_types=1);

return [
    'model'                       => 'Befehl',
    'permissions'                 => [
    ],
    'exceptions'                  => [
    ],
    'validations'                 => [
    ],
    'actions'                     => [
        'pay' => 'Jetzt bezahlen',
    ],
    'enum'                        => [
        'pending'           => 'Unter Prüfung',
        'rejected'          => 'Abgelehnt',
        'processing'        => 'In Reparatur',
        'failed'            => 'Fehlgeschlagen',
        'cancelled_by_user' => 'Vom Benutzer abgebrochen',
        'completed'         => 'Auf Zahlung wartend',
        'paid'              => 'Bezahlt',
        'delivered'         => 'Geliefert',
        'received'          => 'Erhalten',
    ],
    'notifications'               => [
    ],
    'page'                        => [
    ],
    'user'                        => 'User',
    'select_user'                 => 'Select User',
    'user_name'                   => 'User Name',
    'user_email'                  => 'User Email',
    'user_phone'                  => 'User Phone',
    'total'                       => 'Order Total Price',
    'order_number'                => 'Order Number',
    'tracking_code'               => 'Tracking Code',
    'order_number_auto_generated' => 'Order number is automatically generated',
    'total_currency_hint'         => 'Enter amount in your local currency',
    'tracking_code_hint'          => 'Optional tracking code for order monitoring',
    'status'                      => 'Order Status',
    'select_status'               => 'Select Order Status',
    'brand'                       => 'Brand',
    'select_brand'                => 'Select Brand',
    'device'                      => 'Device',
    'select_device'               => 'Select Device',
    'address'                     => 'Address',
    'select_address'              => 'Select Address',
    'payment_method'              => 'Payment Method',
    'select_payment_method'       => 'Select Payment Method',
    'problems'                    => 'Problems',
    'select_problems'             => 'Select Problems',
    'notes'                       => 'Notes',
    'user_note'                   => 'User Note',
    'admin_note'                  => 'Admin Note',
    'user_note_placeholder'       => 'User has not added any notes yet',
    'admin_note_placeholder'      => 'Put your notes here...',
    'device_brand_info'           => 'Device & Brand Information',
    'user_info'                   => 'User Information',
    'address_payment_info'        => 'Address & Payment Information',
    'data'                        => 'Data',
    'media'                       => 'Media',
    'images'                      => 'Images',
    'videos'                      => 'Videos',
];
