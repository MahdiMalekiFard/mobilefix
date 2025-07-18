<?php

declare(strict_types=1);

return [
    'model' => 'Settings',
    'setting_store_succesfully' => 'Settings were saved successfully',

    'configs' => [
        'product' => [
            'label'  => 'Product',
            'hint'   => 'Product',
            'help'   => 'Update product settings here.',
            'groups' => [
                'product_title' => [
                    'label' => 'Product Name',
                    'help'  => 'Define what the product name should include',
                ],
                'product_price' => [
                    'label' => 'Product Price',
                    'help'  => 'Settings related to product pricing',
                ],
                'product_detail' => [
                    'label' => 'Product Details',
                    'help'  => 'Settings related to product details',
                ],
            ],
            'items' => [
                'show_sku' => [
                    'label' => 'Show SKU',
                    'hint'  => 'hint',
                    'help'  => 'help',
                ],
                'show_attribute' => [
                    'label' => 'Show Attributes',
                    'hint'  => '',
                    'help'  => '',
                ],
                'show_brand' => [
                    'label' => 'Show Brand',
                    'hint'  => '',
                    'help'  => '',
                ],
                'show_category' => [
                    'label' => 'Show Category',
                    'hint'  => '',
                    'help'  => '',
                ],
                'show_out_of_stock_products_price' => [
                    'label' => 'Show Price of Out-of-Stock Products',
                    'hint'  => '',
                    'help'  => 'Display price instead of “Out of stock” text',
                ],
                'show_number_of_added_to_carts_in_product_detail' => [
                    'label' => 'Show Number Added to Carts in Product Details',
                    'hint'  => '',
                    'help'  => '',
                ],
                'show_number_of_sold_in_product_detail' => [
                    'label' => 'Show Number Sold in Product Details',
                    'hint'  => '',
                    'help'  => '',
                ],
            ],
        ],

        'general' => [
            'label' => 'General Settings',
            'hint'  => 'General Settings',
            'help'  => 'Update base settings here.',
            'groups' => [
                'company' => [
                    'label' => 'Company Information',
                    'help'  => 'Update company information like address, phone number, etc.',
                ],
                'website' => [
                    'label' => 'Website Information',
                    'help'  => 'Update website settings like logo, calendar, etc.',
                ],
            ],
            'items' => [
                'calendar_value' => [
                    'label' => 'System Calendar',
                    'hint'  => 'Persian | Hijri | Gregorian',
                    'help'  => 'Select the system calendar',
                ],
                'logo' => [
                    'label' => 'Site Logo',
                    'hint'  => 'Site logo',
                    'help'  => 'Upload the desired site logo here',
                ],
                'address' => [
                    'label' => 'Address',
                    'hint'  => 'Mashhad...',
                    'help'  => 'Enter the company address',
                ],
                'tell' => [
                    'label' => 'Phone Number',
                    'hint'  => '0531-1234567',
                    'help'  => 'Enter the company phone number',
                ],
                'name' => [
                    'label' => 'Company Name',
                    'hint'  => 'Company name',
                    'help'  => 'Enter the company name',
                ],
                'postal_code' => [
                    'label' => 'Postal Code',
                    'hint'  => '1234567899',
                    'help'  => 'Enter the postal code',
                ],
                'phone' => [
                    'label' => 'Mobile Number',
                    'hint'  => '09100000000',
                    'help'  => 'Enter the mobile number',
                ],
                'email' => [
                    'label' => 'Email',
                    'hint'  => 'example@gmail.com',
                    'help'  => 'Enter the company email',
                ],
                'latitude' => [
                    'label' => 'Latitude',
                    'hint'  => '59.61602747',
                    'help'  => 'Enter the latitude',
                ],
                'longitude' => [
                    'label' => 'Longitude',
                    'hint'  => '36.28832538',
                    'help'  => 'Enter the longitude',
                ],
                'bank_uuid' => [
                    'label' => 'Default Bank',
                    'hint'  => '',
                    'help'  => 'This bank will be shown as the reference on invoices',
                ],
                'media_id' => [
                    'label' => 'Default Image',
                    'hint'  => 'Default image',
                    'help'  => 'Upload your default image here',
                ],
            ],
        ],

        'integration_sync' => [
            'label' => 'Software Integration',
            'hint'  => 'Software Integration',
            'help'  => 'Update synchronization settings here.',
            'groups' => [
                'mahak' => [
                    'label' => 'Mahak',
                    'help'  => 'Mahak',
                ],
                'orash' => [
                    'label' => 'Orash',
                    'help'  => 'Orash',
                ],
            ],
            'items' => [
                'status'    => ['label' => 'Status', 'hint' => '', 'help' => ''],
                'url'       => ['label' => 'Server Address', 'hint' => '', 'help' => ''],
                'user_name' => ['label' => 'Username', 'hint' => '', 'help' => ''],
                'password'  => ['label' => 'Password', 'hint' => '', 'help' => ''],
                'code'      => ['label' => 'Code', 'hint' => '', 'help' => ''],
            ],
        ],

        'notification' => [
            'label' => 'Notifications',
            'hint'  => 'Notifications',
            'help'  => 'Update notification settings here.',
            'groups' => [
                'order_create' => ['label' => 'New Order', 'help' => 'New Order'],
                'chat_create'  => ['label' => 'New Chat', 'help' => 'New Chat'],
                'user_create'  => ['label' => 'New User Registration', 'help' => 'New User Registration'],
            ],
            'items' => [
                'sms'          => ['label' => 'SMS', 'hint' => '', 'help' => ''],
                'email'        => ['label' => 'Email', 'hint' => '', 'help' => ''],
                'notification' => ['label' => 'Notification', 'hint' => '', 'help' => ''],
            ],
        ],

        'sale' => [
            'label' => 'Sales',
            'hint'  => 'Sales',
            'help'  => 'Update sales settings here.',
            'groups' => [
                'order'    => ['label' => 'Orders', 'help' => 'Update order settings here.'],
                'cart'     => ['label' => 'Cart', 'help' => 'Update cart settings here.'],
                'discount' => ['label' => 'Discount Code', 'help' => 'Update discount code settings here.'],
                'payment'  => ['label' => 'Payment', 'help' => 'Update payment settings here.'],
                'shipping' => ['label' => 'Shipping', 'help' => 'Update shipping settings here.'],
                'target'   => ['label' => 'Sales Target', 'help' => 'Set your sales targets here.'],
            ],
            'items' => [
                'get_quantity_from_accounting' => [
                    'label' => 'Get Product Quantity from Accounting',
                    'hint'  => '',
                    'help'  => 'Enable to fetch product quantities from accounting',
                ],
                'order_return_days_limit' => [
                    'label' => 'Return Deadline (days)',
                    'hint'  => '',
                    'help'  => 'Maximum number of days after payment to return an order',
                ],
                'expiration_days_limit' => [
                    'label' => 'Order Expiry Limit (days)',
                    'hint'  => '',
                    'help'  => 'Order will expire this many days after being placed',
                ],
                'need_to_approve_by_storekeeper' => [
                    'label' => 'Approval Required by Storekeeper',
                    'hint'  => '',
                    'help'  => 'If enabled, orders will require storekeeper approval before proceeding',
                ],
                'cart_capacity' => [
                    'label' => 'Cart Capacity Limit',
                    'hint'  => '',
                    'help'  => 'Prevent adding more items to the cart than this limit',
                ],
                'apply_discount_code_only_for_cash_payment' => [
                    'label' => 'Apply Discount Code Only for Cash Payment',
                    'hint'  => '',
                    'help'  => '',
                ],
                'credit_card_deposit' => [
                    'label' => 'Card-to-Card Deposit',
                    'hint'  => '',
                    'help'  => 'Enable or disable card-to-card deposit option',
                ],
                'free_shipping' => [
                    'label' => 'Free Shipping',
                    'hint'  => '',
                    'help'  => 'Set minimum order value for free shipping',
                ],
                'monthly' => [
                    'label' => 'Monthly',
                    'hint'  => '1',
                    'help'  => 'Enter the monthly sales target',
                ],
                'semi_annual' => [
                    'label' => 'Semi-Annual',
                    'hint'  => '50000',
                    'help'  => 'Enter the semi-annual sales target',
                ],
                'yearly' => [
                    'label' => 'Yearly',
                    'hint'  => '51245612',
                    'help'  => 'Enter the yearly sales target',
                ],
            ],
        ],

        'security' => [
            'label' => 'Security',
            'hint'  => 'Security',
            'help'  => 'Update security settings here.',
            'items' => [
                'captcha_handling' => [
                    'label' => 'Captcha Management',
                    'hint'  => 'Enable and configure captcha',
                    'help'  => 'Enable captcha and manage its settings to enhance security.',
                ],
            ],
        ],
    ],
];
