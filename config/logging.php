<?php


return [

    /*
    |--------------------------------------------------------------------------
    | Default Log Channel
    |--------------------------------------------------------------------------
    |
    | This option defines the default log channel that gets used when writing
    | messages to the logs. The name specified in this option should match
    | one of the channels defined in the "channels" configuration array.
    |
    */

    'default' => env('LOG_CHANNEL', 'stack'),

    /*
    |--------------------------------------------------------------------------
    | Log Channels
    |--------------------------------------------------------------------------
    |
    | Here you may configure the log channels for your application. Out of
    | the box, Laravel uses the Monolog PHP logging library. This gives
    | you a variety of powerful log handlers / formatters to utilize.
    |
    | Available Drivers: "single", "daily", "slack", "syslog",
    |                    "errorlog", "monolog",
    |                    "custom", "stack"
    |
    */

    'channels' => [
        /*
         *
         * MULTI CHANNEL LOG
         *
         * */
        'backend'    => [
            'name'     => 'Backend',
            'driver'   => 'stack',
            'channels' => [
                empty(env('LOG_BACKEND_CHANNEL', 'stderr')) ? 'none-disk' : env('LOG_BACKEND_CHANNEL', 'stderr'),
            ],
            'level'  => empty(env('APP_LOG_LEVEL', 'error')) ? 'error' : env('APP_LOG_LEVEL', 'error'),
        ],
        'database'   => [
            'name'     => 'Database',
            'driver'   => 'stack',
            'channels' => [
                empty(env('LOG_DATABASE_CHANNEL', 'stderr')) ? 'stderr'
                    : env('LOG_DATABASE_CHANNEL', 'stderr'),
            ],
            'level'  => empty(env('APP_LOG_LEVEL', 'error')) ? 'error' : env('APP_LOG_LEVEL', 'error'),
        ],
        'stack'      => [
            'driver'   => 'stack',
            'channels' => [
                empty(env('LOG_ELASTIC_CHANNEL', 'stderr')) ? 'stderr'
                    : env('LOG_ELASTIC_CHANNEL', 'stderr'),
            ],
        ],
        'single'     => [
            'driver'   => 'stack',
            'channels' => [
                empty(env('LOG_ELASTIC_CHANNEL', 'stderr')) ? 'stderr'
                    : env('LOG_ELASTIC_CHANNEL', 'stderr'),
            ],
        ],
        'access_log' => [
            'driver'   => 'stack',
            'channels' => [
                empty(env('LOG_ELASTIC_CHANNEL', 'stderr')) ? 'stderr'
                    : env('LOG_ELASTIC_CHANNEL', 'stderr'),
            ],
        ],

        'db_log' => [
            'driver'   => 'stack',
            'channels' => [
                empty(env('LOG_ELASTIC_CHANNEL', 'stderr')) ? 'stderr'
                    : env('LOG_ELASTIC_CHANNEL', 'stderr'),
            ],
        ],
        'rapi_errors' => [
            'driver'   => 'stack',
            'channels' => [
                empty(env('LOG_ELASTIC_CHANNEL', 'stderr')) ? 'stderr'
                    : env('LOG_ELASTIC_CHANNEL', 'stderr'),
            ],
        ],

        'scratch_cards' => [
            'driver'   => 'stack',
            'channels' => [
                empty(env('LOG_ELASTIC_CHANNEL', 'stderr')) ? 'stderr'
                    : env('LOG_ELASTIC_CHANNEL', 'stderr'),
            ],
        ],

        'games' => [
            'driver'   => 'stack',
            'channels' => [
                empty(env('LOG_ELASTIC_CHANNEL', 'stderr')) ? 'stderr'
                    : env('LOG_ELASTIC_CHANNEL', 'stderr'),
            ],
        ],

        'daily' => [
            'driver'   => 'stack',
            'channels' => [
                empty(env('LOG_ELASTIC_CHANNEL', 'stderr')) ? 'stderr'
                    : env('LOG_ELASTIC_CHANNEL', 'stderr'),
            ],
            'days'     => 7,
        ],

        'syslog' => [
            'driver'   => 'stack',
            'channels' => [
                empty(env('LOG_ELASTIC_CHANNEL', 'stderr')) ? 'stderr'
                    : env('LOG_ELASTIC_CHANNEL', 'stderr'),
            ],
        ],

        'errorlog' => [
            'driver'   => 'stack',
            'channels' => [
                empty(env('LOG_ELASTIC_CHANNEL', 'stderr')) ? 'stderr'
                    : env('LOG_ELASTIC_CHANNEL', 'stderr'),
            ],
        ],
        //elastic
        'custom'   => [
            'driver'   => 'stack',
            'channels' => [
                empty(env('LOG_ELASTIC_CHANNEL', 'stderr')) ? 'stderr'
                    : env('LOG_ELASTIC_CHANNEL', 'stderr'),
            ],
        ],

        'reports'  => [
            'driver'   => 'stack',
            'channels' => [ 'stderr' ],
            'days'     => 7,
        ],

        /*
         * only channel
         *
         * */

        /*
         * SLACK CHANNEL
         * */
        'slack' => env('LOG_SLACK_WEBHOOK_URL', null) === null ? [] :
            [
                'driver'   => 'slack',
                'url'      => env('LOG_SLACK_WEBHOOK_URL'),
                'username' => 'RAPI Log',
                'emoji'    => ':boom:',
                'short'    => true,
            ],

        'rapi_errors_single' => env('LOG_SLACK_WEBHOOK_URL_ERROR', null) === null ? [] : [
            'driver'   => 'slack',
            'url'      => env('LOG_SLACK_WEBHOOK_URL_ERROR'),
            'username' => 'RAPI Log',
            'emoji'    => ':boom:',
            'short'    => true,
        ],

        'slack_unit_test' => env('LOG_SLACK_WEBHOOK_URL_TESTS', null) === null ? [] :
            [
                'driver'   => 'slack',
                'url'      => env('LOG_SLACK_WEBHOOK_URL_TESTS'),
                'username' => 'Laravel Log',
                'emoji'    => ':boom:',
                'short'    => true,
            ],

        /*
         *
         * OUTPUT CHANNEL
         *
         *
         * */

        'stderr' => [
            'driver'    => 'monolog',
            'handler'   => \Monolog\Handler\StreamHandler::class,
            'formatter' => env('LOG_STDERR_FORMATTER'),
            'with'      => [
                'stream' => 'php://stderr',
            ],
        ],
        'backend-disk' => [
            'name'   => 'backend',
            'driver' => 'single',
            'path'   => storage_path('logs/backend.log'),
        ],

        'database-disk' => [
            'name'   => 'database',
            'driver' => 'single',
            'path'   => storage_path('logs/database.log'),
        ],
        'elastic-disk'  => [
            'name'   => 'elastic-file',
            'driver' => 'single',
            'path'   => storage_path('logs/elastic-file.log'),
        ],
        'none-disk'     => [],
    ],

];


