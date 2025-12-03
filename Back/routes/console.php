<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

Artisan::command(
  'inspire',
  function () {
    $this->comment(Inspiring::quote());
  }
)->describe('Display an inspiring quote');

// Команда консоли для очистки логов
// php artisan delete:logs
Artisan::command(
  'delete:logs',
  function () {
    $path = '/logs/';
    if ($path != false) {
      if (Storage::exists($path)) {
        Storage::deleteDirectory($path);
        Storage::makeDirectory($path);
        $this->info('Logs cleaned!');
      } else {
        $this->error('Logs folder not found');
      }
    } else {
      $this->error('Path not entered');
    }
  }
)->describe('Remove the logs from logs directory');
