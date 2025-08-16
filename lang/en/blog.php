<?php

return [
    'model'         => 'Blog',
    'permissions'   => [
    ],
    'exceptions'    => [
        'published_at_after_now' => 'The published at date must be at least 2 minutes in the future.',
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
        'published_at_explanation' => 'Set a future date and time when this blog should be published.',
        'will_publish_immediately' => 'This blog will be published immediately when saved.',
    ],
];
