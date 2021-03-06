<?php

$url = parse_url(getenv('JAWSDB_URL'));

$host = isset($url['host']) ? $url['host'] : env('DB_HOST', 'localhost');
$username = isset($url['user']) ? $url['user'] : env('DB_USERNAME', 'forge');
$password = isset($url['pass']) ? $url['pass'] : env('DB_PASSWORD', '');
$database = isset($url['host']) ? ltrim($url['path'], '/') : env('DB_DATABASE', 'forge');

// REDIS - Split out configuration into an array
if (env('REDIS_SERVERS', false)) {
    $redisServerKeys = ['host', 'port', 'database'];
    $redisServers = explode(',', trim(env('REDIS_SERVERS', '127.0.0.1:6379:0'), ','));
    $redisConfig = [
        'cluster' => env('REDIS_CLUSTER', false)
    ];
    foreach ($redisServers as $index => $redisServer) {
        $redisServerName = ($index === 0) ? 'default' : 'redis-server-' . $index;
        $redisServerDetails = explode(':', $redisServer);
        if (count($redisServerDetails) < 2) $redisServerDetails[] = '6379';
        if (count($redisServerDetails) < 3) $redisServerDetails[] = '0';
        $redisConfig[$redisServerName] = array_combine($redisServerKeys, $redisServerDetails);
    }
}

$mysql_host = env('DB_HOST', 'localhost');
$mysql_host_exploded = explode(':', $mysql_host);
$mysql_port = env('DB_PORT', 3306);
if (count($mysql_host_exploded) > 1) {
    $mysql_host = $mysql_host_exploded[0];
    $mysql_port = intval($mysql_host_exploded[1]);
}

return [

    /*
    |--------------------------------------------------------------------------
    | PDO Fetch Style
    |--------------------------------------------------------------------------
    |
    | By default, database results will be returned as instances of the PHP
    | stdClass object; however, you may desire to retrieve records in an
    | array format for simplicity. Here you can tweak the fetch style.
    |
    */

    'fetch' => PDO::FETCH_CLASS,

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
            'driver'   => 'sqlite',
            'database' => storage_path('database.sqlite'),
            'prefix'   => '',
        ],

        'mysql' => [
            'driver'    => 'mysql',
            'host'      => $host,
            'database'  => $database,
            'username'  => $username,
            'password'  => $password,
            'charset'   => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix'    => '',
            'strict'    => false,
        ],

        'mysql_testing' => [
            'driver'    => 'mysql',
            'host'      => '127.0.0.1',
            'database'  => 'bookstack-test',
            'username'  => env('MYSQL_USER', 'bookstack-test'),
            'password'  => env('MYSQL_PASSWORD', 'bookstack-test'),
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
            'strict'    => false,
        ],

        'pgsql' => [
            'driver'   => 'pgsql',
            'host'     => $host,
            'database' => $database,
            'username' => $username,
            'password' => $password,
            'charset'  => 'utf8',
            'prefix'   => '',
            'schema'   => 'public',
        ],

        'sqlsrv' => [
            'driver'   => 'sqlsrv',
            'host'     => $host,
            'database' => $database,
            'username' => $username,
            'password' => $password,
            'charset'  => 'utf8',
            'prefix'   => '',
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
    | provides a richer set of commands than a typical key-value systems
    | such as APC or Memcached. Laravel makes it easy to dig right in.
    |
    */

    'redis' => env('REDIS_SERVERS', false) ? $redisConfig : [],

];
