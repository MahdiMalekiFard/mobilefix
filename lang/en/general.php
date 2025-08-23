<?php

declare(strict_types=1);

return [
    'show'                               => 'Show',
    'create'                             => 'Create',
    'edit'                               => 'Edit',
    'delete'                             => 'Delete',
    'back'                               => 'Back',
    'submit'                             => 'Submit',
    'cancel'                             => 'Cancel',
    'fa'                                 => 'Persian',
    'en'                                 => 'English',

    'please_select_an_option'            => 'Please select an option',
    'yes'                                => 'Yes',
    'no'                                 => 'No',
    'active'                             => 'Active',
    'inactive'                           => 'Inactive',

    'model_has_stored_successfully'      => ':model was successfully stored',
    'model_has_updated_successfully'     => ':model was successfully updated',
    'model_has_deleted_successfully'     => ':model was successfully deleted',
    'model_has_toggled_successfully'     => ':model status was successfully changed',
    'model_has_upload_successfully'      => ':model was successfully uploaded',
    'model_has_Failed_to_upload'         => ':model failed to upload',
    'model_has_retrieved_successfully'   => ':model was successfully retrieved',
    'model_has_Failed_to_store'          => ':model failed to store',
    'model_has_not_set'                  => 'Model is not defined.',
    'model_has_updated_failed'           => ':model update failed. Please report the issue',
    'model_has_set_default_successfully' => ':model was successfully set as default',
    'model_has_set_default_failed'       => ':model set default failed. Please report the issue',

    'store_success'                      => ':model was successfully created',
    'store_failed'                       => ':model creation failed. Please report the issue',

    'update_success'                     => ':model was successfully updated',
    'update_failed'                      => ':model update failed. Please report the issue',

    'delete_success'                     => ':model was successfully deleted',
    'delete_failed'                      => ':model deletion failed. Please report the issue',
    'delete_can_not'                     => 'You do not have permission to delete :model',

    'toggle_success'                     => ':model status changed successfully',
    'toggle_failed'                      => ':model status change failed. Please report the issue',
    'toggle_can_not'                     => 'You do not have permission to change the status of :model',

    'menu'                               => [
        'index' => ':model list',
    ],

    'page'                               => [
        'index'   => [
            'page_title' => ':model List',
            'title'      => 'All :model Items',
            'desc'       => 'All available :model items in the system',
            'create'     => 'New :model',
        ],
        'create'  => [
            'page_title' => 'Create :model',
            'title'      => 'Register New :model',
            'desc'       => 'Please ensure you have content manager approval before creating a new item',
        ],
        'edit'    => [
            'page_title' => 'Edit :model',
            'title'      => 'Update :model',
            'desc'       => 'Please ensure you have content manager approval before editing this item',
        ],
        'show'    => [
            'page_title' => ':model Details',
            'title'      => 'Details of :model',
            'desc'       => 'All details of :model',
        ],
        'public'  => [
            'info'         => 'Information',
            'addresses'    => 'Addresses',
            'security'     => 'Security',
            'ticket_reply' => 'Reply to Ticket',
            'permissions'  => 'Permissions',
            'exceptions'   => 'Exceptions',
            'rules'        => 'Rules',
        ],
        'buttons' => [
            'add' => 'Add',
        ],
    ],

    'exceptions'                         => [
        'bad_request' => 'Bad Request',
        'not_allowed' => 'Operation Not Allowed',
    ],

    'page_sections'                      => [
        'data'                 => 'Information',
        'social'               => 'Social Media',
        'ordering'             => 'Order Settings',
        'seo_options'          => 'SEO Settings',
        'upload_image'         => 'Upload Image',
        'publish_config'       => 'Publish Settings',
        'status'               => 'Status',
        'special'              => 'Make Special',
        'end_of_work_settings' => 'End-of-Work Settings',
    ],

    'signs'                              => [
        '='  => 'Equals',
        '>'  => 'Greater Than',
        '<'  => 'Less Than',
        '>=' => 'Greater Than or Equal To',
        '<=' => 'Less Than or Equal To',
    ],
    'profile'                            => 'Profile',
    'personal_information'               => 'Personal Information',
    'favorite'                           => 'Favorites',
    'transactions'                       => 'Transactions',
    'activities'                         => 'Activities',
    'views'                              => 'Views',
    'currency'                           => 'USD',
    'not_set'                            => 'Not Set',
    'price_from'                         => 'From',
    'price_up_to'                        => 'Up to',

    'calendar'                           => [
        'persian'   => 'Persian Calendar',
        'gregorian' => 'Gregorian Calendar',
        'hijri'     => 'Hijri Calendar',
    ],
    'close'                              => 'Close',
    'reload'                             => 'Reload',
    'reset'                              => 'Reset',
    'are_you_shure_to_delete_record'     => 'Are you sure you want to delete this record?',
    'roles'                              => 'Roles',
];
