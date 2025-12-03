
<?php

return [
  'paths' => ['api/*', 'sanctum/csrf-cookie'],
  'paths' => ['*'],
  'allowed_methods' => ['GET', 'POST', 'OPTIONS', 'DELETE', 'PUT'],
  // 'allowed_origins' => ['*'],
  'allowed_origins' => ['http://localhost:8089', 'https://webdserv', 'https://webdserv:8080', 'http://localhost:8080'],
  'allowed_origins_patterns' => [],
  'allowed_headers' => ['*'],
  'exposed_headers' => [],
  'max_age' => 0,
  //'supports_credentials' => env('CORS_SUPPORT_CREDENTIALS', false),
  'supports_credentials' => true,

];
