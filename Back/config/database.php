<?php

use Illuminate\Support\Str;

return [
  'default'     => env('DB_CONNECTION', 'pgsql'),

  'connections' => [
    'pgsql' => [
      'driver'         => 'pgsql',
      'host'           => env('DB_HOST', ''),
      'port'           => env('DB_PORT', '5432'),
      'database'       => env('DB_DATABASE', ''),
      'username'       => env('DB_USERNAME', ''),
      'password'       => env('DB_PASSWORD', ''),
      'charset'        => env('DB_CHARSET', 'UTF8'),
      'prefix'         => '',
      'prefix_indexes' => true,
      'schema'         => env('DB_SCHEMA_PREFIX', ''),
      'sslmode'        => 'prefer',
      'options'        => [
        PDO::ATTR_PERSISTENT => false,
      ],
    ],
    'pgsqltest' => [
      'driver'         => 'pgsql',
      'host'           => env('DB_TEST_HOST', ''),
      'port'           => env('DB_TEST_PORT', '5432'),
      'database'       => env('DB_TEST_DATABASE', ''),
      'username'       => env('DB_TEST_USERNAME', ''),
      'password'       => env('DB_TEST_PASSWORD', ''),
      'charset'        => env('DB_TEST_CHARSET', 'UTF8'),
      'prefix'         => '',
      'prefix_indexes' => true,
      'schema'         => env('DB_SCHEMA_PREFIX', ''),
      'sslmode'        => 'prefer',
      'options'        => [
        PDO::ATTR_PERSISTENT => false,
      ],
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

    'client' => env('REDIS_CLIENT', 'phpredis'),

    'options' => [
      'cluster' => env('REDIS_CLUSTER', 'redis'),
      'prefix'  => env('REDIS_PREFIX', Str::slug(env('APP_NAME', 'laravel'), '_') . '_database_'),
    ],

    'default' => [
      'url'      => env('REDIS_URL'),
      'host'     => env('REDIS_HOST', '127.0.0.1'),
      'password' => env('REDIS_PASSWORD', null),
      'port'     => env('REDIS_PORT', '6379'),
      'database' => env('REDIS_DB', '0'),
    ],

    'cache' => [
      'url'      => env('REDIS_URL'),
      'host'     => env('REDIS_HOST', '127.0.0.1'),
      'password' => env('REDIS_PASSWORD', null),
      'port'     => env('REDIS_PORT', '6379'),
      'database' => env('REDIS_CACHE_DB', '1'),
    ],

  ],

];
