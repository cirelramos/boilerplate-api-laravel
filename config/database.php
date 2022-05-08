<?php


$mysqlConnection = env('TYPE_MYSQL_CONNECTION', null);

$mysql = [
    'driver' => 'vault',
];

$mysqlTest = [
    'driver' => 'vault',
];

$mysqlAdminLang = [
    'driver' => 'vault',
];


$mysqlAdminLangTest = [
    'driver' => 'vault',
];

$mysqlExternal = [
    'driver' => 'vault',
];

$mysqlExternalTest = [
    'driver' => 'vault',
];

if ($mysqlConnection === 'local' || $mysqlConnection === null) {
    $mysql = [
        'driver' => 'mysql',
        'host' => env('DB_HOST', '127.0.0.1'),
        'port' => env('DB_PORT', '3306'),
        'database' => env('DB_DATABASE', 'apitrillo'),
        'username' => env('DB_USERNAME', 'root'),
        'password' => env('DB_PASSWORD', 'root'),
        'unix_socket' => env('DB_SOCKET', ''),
        'charset' => env('DB_CHARSET_MYSQL', 'utf8mb4'),
        'collation' => env('DB_COLLECTION_MYSQL' , 'utf8mb4_unicode_ci'),
        'prefix' => '',
        'modes' => [
            'NO_UNSIGNED_SUBTRACTION',
            'NO_ENGINE_SUBSTITUTION',
        ],
        'engine' => null,
    ];

    $mysqlTest = [
        'driver' => 'mysql',
        'host' => env('DB_HOST_TEST', '127.0.0.1'),
        'port' => env('DB_PORT_TEST', '3306'),
        'database' => env('DB_DATABASE_TEST', 'apitrillo'),
        'username' => env('DB_USERNAME_TEST', 'root'),
        'password' => env('DB_PASSWORD_TEST', 'root'),
        'unix_socket' => env('DB_SOCKET', ''),
        'charset' => env('DB_CHARSET_MYSQL', 'utf8mb4'),
        'collation' => env('DB_COLLECTION_MYSQL' , 'utf8mb4_unicode_ci'),
        'prefix' => '',
        'modes' => [
            'NO_UNSIGNED_SUBTRACTION',
            'NO_ENGINE_SUBSTITUTION',
        ],
        'engine' => null,
    ];

    $mysqlExternal = [
        'driver' => 'mysql',
        'host' => env('DB_HOST2', '127.0.0.1'),
        'port' => env('DB_PORT2', '3306'),
        'database' => env('DB_DATABASE2', 'trillonario'),
        'username' => env('DB_USERNAME2', 'root'),
        'password' => env('DB_PASSWORD2', 'root'),
        'unix_socket' => env('DB_SOCKET', ''),
        'charset' => env('DB_CHARSET_MYSQL', 'utf8mb4'),
        'collation' => env('DB_COLLECTION_MYSQL' , 'utf8mb4_unicode_ci'),
        'prefix' => '',
        'modes' => [
            'NO_UNSIGNED_SUBTRACTION',
            'NO_ENGINE_SUBSTITUTION',
        ],
        'engine' => null,
    ];

    $mysqlAdminLang = [
        'driver' => 'mysql',
        'host' => env('DB_HOST_ADMIN_LANG', '127.0.0.1'),
        'port' => env('DB_PORT_ADMIN_LANG', '3306'),
        'database' => env('DB_DATABASE_ADMIN_LANG', 'admin_lang'),
        'username' => env('DB_USERNAME_ADMIN_LANG', 'root'),
        'password' => env('DB_PASSWORD_ADMIN_LANG', 'root'),
        'unix_socket' => env('DB_SOCKET', ''),
        'charset' => env('DB_CHARSET_MYSQL', 'utf8mb4'),
        'collation' => env('DB_COLLECTION_MYSQL' , 'utf8mb4_unicode_ci'),
        'prefix' => '',
        'modes' => [
            'NO_UNSIGNED_SUBTRACTION',
            'NO_ENGINE_SUBSTITUTION',
        ],
        'engine' => null,
    ];


    $mysqlExternalTest = [
        'driver' => 'mysql',
        'host' => env('DB_HOST2_TEST', '127.0.0.1'),
        'port' => env('DB_PORT2_TEST', '3306'),
        'database' => env('DB_DATABASE2_TEST', 'trillonario'),
        'username' => env('DB_USERNAME2_TEST', 'root'),
        'password' => env('DB_PASSWORD2_TEST', 'root'),
        'unix_socket' => env('DB_SOCKET', ''),
        'charset' => env('DB_CHARSET_MYSQL', 'utf8mb4'),
        'collation' => env('DB_COLLECTION_MYSQL' , 'utf8mb4_unicode_ci'),
        'prefix' => '',
        'modes' => [
            'NO_UNSIGNED_SUBTRACTION',
            'NO_ENGINE_SUBSTITUTION',
        ],
        'engine' => null,
    ];

    $mysqlExternal = env('DB_CONNECTION2', 'mysql_external') === 'mysql_external' ? $mysqlExternal : $mysqlExternalTest;
}

$database = [

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for all database work. Of course
    | you may use many connections at once using the Database library.
    |
    */

    'default' => env('DB_CONNECTION', 'mysql'),

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the database connections setup for your application.
    | Of course, examples of configuring each database platform that is
    | supported by Laravel is shown below to make development simple.
    |
    |
    | All database work in Laravel is done through the PHP PDO facilities
    | so make sure you have the driver for your particular database of
    | choice installed on your machine before you begin development.
    |
    */

    'connections' => [

        'sqlite' => [
            'driver' => 'sqlite',
            'url' => env('DATABASE_URL'),
            'database' => env('DB_DATABASE', database_path('database.sqlite')),
            'prefix' => '',
            'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
        ],

        'mysql' => $mysql,

        'mysql_test' => $mysqlTest,

        'mysql_adminlang' => $mysqlAdminLang,


        'mysql_external' => $mysqlExternal,

        'mysql_external_test' => $mysqlExternalTest,

        'mysql_replica' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST3', '127.0.0.1'),
            'port' => env('DB_PORT3', '3306'),
            'database' => env('DB_DATABASE3', 'forge'),
            'username' => env('DB_USERNAME3', 'forge'),
            'password' => env('DB_PASSWORD3', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'latin1',
            'collation' => 'latin1_swedish_ci',
            'prefix' => '',
            'modes' => [
                'NO_UNSIGNED_SUBTRACTION',
                'NO_ENGINE_SUBSTITUTION',
            ],
            'engine' => null,
        ],

        'mysql_reports' => [
            'driver' => 'vault',
        ],

        'pgsql' => [
            'driver' => 'pgsql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '5432'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'search_path' => 'public',
            'sslmode' => 'prefer',
        ],

        'sqlsrv' => [
            'driver' => 'sqlsrv',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', 'localhost'),
            'port' => env('DB_PORT', '1433'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            // 'encrypt' => env('DB_ENCRYPT', 'yes'),
            // 'trust_server_certificate' => env('DB_TRUST_SERVER_CERTIFICATE', 'false'),
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Migration Repository Table
    |--------------------------------------------------------------------------
    |
    | This table keeps track of all the migrations that have already run for
    | your application. Using this information, we can determine which of
    | the migrations on disk haven't actually been run in the database.
    |
    */

    'migrations' => 'migrations',

    /*
    |--------------------------------------------------------------------------
    | Redis Databases
    |--------------------------------------------------------------------------
    |
    | Redis is an open source, fast, and advanced key-value store that also
    | provides a richer body of commands than a typical key-value system
    | such as APC or Memcached. Laravel makes it easy to dig right in.
    |
    */

    'redis' => [
        'client'   => env('REDIS_CLIENT', 'phpredis'),
        'clusters' => [
            'default' => [
                [
                    'host'     => env('REDIS_HOST', 'localhost'),
                    'password' => env('REDIS_PASSWORD', null),
                    'port'     => env('REDIS_PORT', 6379),
                    'database' => 0,
                ],
            ],
        ],

    ],
];

if (env('REDIS_CLUSTER', true) === false) {
    $database[ 'redis' ] = [
        'client'  => 'phpredis',
        'default' => [
            'host'           => env('REDIS_HOST', '127.0.0.1'),
            'password'       => env('REDIS_PASSWORD', null),
            'port'           => env('REDIS_PORT', 6379),
            'database'       => 0,
            'timeout'        => 60,
            'read_timeout'   => 60,
            'retry_interval' => 3,
        ],
    ];
}

return $database;
