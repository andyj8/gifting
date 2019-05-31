<?php

return [
    'rabbit' => [
        'vhosts' => [
            'gifting' => [
                'exchanges' => []
            ]
        ]
    ],
    'db' => [
        'driver' => 'pdo_mysql'
    ],
    'mail' => [
        'use_test_account' => true,
        'test_account'     => null,
        'mailchimp'        => [
            'api_key' => '123',
        ],
        'proxy'            => null,
    ],
    'logger' => [
        'api_logdir'    => null,
        'worker_logdir' => null,
        'debug_logging' => false,
    ],
    'delivery' => [
        'same_day_cutoff' => '1500'
    ],
    'tribeka' => [
        'host' => 'http://81.187.12.194:8731',
        'resource' => '/api/GreetingCard'
    ],
    'voucher' => [
        'lifetime' => 365, // days
        'code' => [
            "format" => "??-????-????",
            'avail_chars' => 'ABCDEFGHJKLMNPQRTUVWXYZ2346789',
            "prefix_map" => [
                "magazine"     => "MA",
                "subscription" => "MS",
                "book"         => "BK",
                "release"      => "MU",
                "track"        => "MU",
                "game"         => "GA",
                "movie"        => "MV"
            ]
        ]
    ],
    'dev_mode' => true,
];
