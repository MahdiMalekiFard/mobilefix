<?php

return [
    'model'         => 'Order',
    'permissions'   => [
    ],
    'exceptions'    => [
    ],
    'validations'   => [
    ],
    'enum'          => [
        'pending' => 'Under Review',
        'rejected' => 'Rejected',
        'processing' => 'In Repair',
        'failed' => 'Failed',
        'cancelled_by_user' => 'Cancelled by User',
        'completed' => 'Awaiting Payment',
        'paid' => 'Paid',
        'delivered' => 'Delivered',
        'received' => 'Received',
    ],
    'notifications' => [
    ],
    'page'          => [
    ],
    'user_email' => 'User Email',
    'total' => 'Order Total Price',
    'order_number' => 'Order Number',
    'status' => 'Order Status',
    'select_status' => 'Select Order Status',
    'brand' => 'Brand',
    'select_brand' => 'Select Brand',
    'device' => 'Device',
    'select_device' => 'Select Device',
    'address' => 'Address',
    'select_address' => 'Select Address',
    'payment_method' => 'Payment Method',
    'select_payment_method' => 'Select Payment Method',
    'problems' => 'Problems',
    'select_problems' => 'Select Problems',
    'notes' => 'Notes',
    'user_note' => 'User Note',
    'admin_note' => 'Admin Note',
    'user_note_placeholder' => 'Enter your notes here...',
    'admin_note_placeholder' => 'Put your notes here...',
    'device_brand_info' => 'Device & Brand Information',
    'address_payment_info' => 'Address & Payment Information',
    'data' => 'Data',
    'media' => 'Media',
    'images' => 'Images',
    'videos' => 'Videos',
];
