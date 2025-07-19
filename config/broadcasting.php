<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Broadcaster
    |--------------------------------------------------------------------------
    |
    | This option controls the default broadcaster that will be used by the
    | framework when an event needs to be broadcast. You may set this to
    | any of the connections defined in the "connections" array below.
    |
    */

    'default' => env('BROADCAST_DRIVER', 'redis'),

    'connections' => [
    // …

    'redis' => [
        'driver'     => 'redis',
        'connection' => 'default',
    ],

    // هذا لـ laravel-echo-server
    'socket.io' => [
        'driver' => 'pusher',      // يظل 'pusher' ليحاكي بروتوكول Pusher
        'key'    => env('APP_KEY'),// أو أي قيمة عشوائية
        'options' => [
            'cluster'      => env('ECHO_CLUSTER', 'mt1'),
            'host'         => env('ECHO_HOST', request()->getHost()),
            'port'         => env('ECHO_PORT', 6001),
            'scheme'       => env('ECHO_SCHEME', 'http'),
            'encrypted'    => false,
            'useTLS'       => false,
            'disableStats' => true,
        ],
    ],
],


    /*
    |--------------------------------------------------------------------------
    | Broadcast Connections
    |--------------------------------------------------------------------------
    |
    | Here you may define all of the broadcast connections that will be used
    | to broadcast events to other systems or over websockets. Samples of
    | each available type of connection are provided inside this array.
    |
    */
    /*
    'connections' => [

        'pusher' => [
            'driver' => 'pusher',
            'key' => env('PUSHER_APP_KEY'),
            'secret' => env('PUSHER_APP_SECRET'),
            'app_id' => env('PUSHER_APP_ID'),
            'options' => [
                'cluster' => env('PUSHER_APP_CLUSTER'),
                'useTLS' => true,
            ],
        ],

        'ably' => [
            'driver' => 'ably',
            'key' => env('ABLY_KEY'),
        ],

        'redis' => [
            'driver' => 'redis',
            'connection' => env('BROADCAST_REDIS_CONNECTION', 'default'),
        ],

        'log' => [
            'driver' => 'log',
        ],

        'null' => [
            'driver' => 'null',
        ],

    ],
*/
];