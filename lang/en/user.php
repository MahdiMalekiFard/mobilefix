<?php

declare(strict_types=1);

return [
    'model' => 'User',

    'validation' => [
        'name_required'   => 'The name field is required.',
        'family_required' => 'The family name field is required.',
    ],

    'exceptions' => [
        'developer_can_not_removed' => 'The super admin cannot be deleted!',
    ],

    'page' => [
        'name_info'       => 'You can enter up to 255 characters.',
        'family_info'     => 'You can enter up to 255 characters.',
        'password_info'   => 'Minimum 8 characters.',
        'email_info'      => 'Enter your email address.',
        'mobile_info'     => 'Enter your mobile number.',
        'block_info'      => 'You can block this user from here.',
        'user_group_info' => 'Specify the user group this user belongs to.',
    ],

    'messages' => [
        'new_password_updated' => 'The new password was successfully updated.',
        'new_profile_image'    => 'The profile picture was successfully updated.',
    ],
];

