<?php

return [

  'driver' => env('MAIL_DRIVER', 'smtp'),
  'host'   => env('MAIL_HOST', 'cgateserv.local.imf.ru'),
  'port'   => env('MAIL_PORT', 25),

  'from' => [
    'address' => env('MAIL_FROM_ADDRESS', 'no-reply@local.imf.ru'),
    'name'    => env('MAIL_FROM_NAME', 'Server'),
  ],

  'encryption' => env('MAIL_ENCRYPTION', 'tls'),

  'username' => env('MAIL_USERNAME'),
  'password' => env('MAIL_PASSWORD'),

  'sendmail' => '/usr/sbin/sendmail -bs',

  'markdown' => [
    'theme' => 'default',

    'paths' => [
      resource_path('views/vendor/mail'),
    ],
  ],

  'log_channel' => env('MAIL_LOG_CHANNEL'),

  'pretend' => 'true',

];
