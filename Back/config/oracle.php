<?php

return [
  'oracle' => [
    'driver'         => 'oracle',
    'tns'            => env('DB_DATA_TNS', ''),
    'host'           => env('DB_DATA_HOST', ''),
    'port'           => env('DB_DATA_PORT', '1521'),
    'database'       => env('DB_DATA_DATABASE', ''),
    'username'       => env('DB_DATA_USERNAME', ''),
    'password'       => env('DB_DATA_PASSWORD', ''),
    'charset'        => env('DB_DATA_CHARSET', 'AL32UTF8'),
    'prefix'         => env('DB_DATA_PREFIX', ''),
    'prefix_schema'  => env('DB_DATA_SCHEMA_PREFIX', ''),
    'server_version' => env('DB_DATA_SERVER_VERSION', '11g'),
    'load_balance'   => env('DB_DATA_LOAD_BALANCE', 'yes'),
    'dynamic'        => [],
    'options'        => [
      PDO::ATTR_PERSISTENT => false,
    ],
  ],
];
