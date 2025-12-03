<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CardsController;
use App\Http\Controllers\DocumentsController;
use App\Http\Controllers\OperationsController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\StatusController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// Роли 
$roleA = 'PTT05A,PTT05A_1';
$roleB = 'PTT05B,PTT05B_1';

Route::middleware(['cors'])->group(function () use ($roleA, $roleB) {

  // Не обязательны роли
  Route::middleware(['authApi'])->group(function () {

    Route::controller(ApiController::class)->group(function () {
      Route::get('/', 'works');
      Route::post('/log', 'log');
    });

    Route::controller(AuthController::class)->group(function () {
      Route::get('/auth', 'auth');
    });
  });

  // Доступ только роли A:
  // Route::middleware("authApi:$roleA")->group(function () {

  Route::controller(SearchController::class)->group(function () {
    Route::prefix('/search/')->group(function () {
      Route::post('/product', 'searchProduct');
      Route::post('/cards', 'searchCards');
      Route::post('/index', 'searchIndex');
      Route::post('/code', 'searchCode');
      Route::post('/operation', 'searchOperation');
      Route::post('/profession', 'searchProfession');
      Route::post('/hardware', 'searchHardware');
      Route::post('/logs', 'searchLogs');
    });
  });

  Route::controller(CardsController::class)->group(function () {
    Route::prefix('/cards')->group(function () {
      Route::post('/', 'createCard');
      Route::get('/{cardID}', 'getCard')
        ->whereNumber('cardID');
      Route::post('/show', 'showCards');
      Route::put('/', 'saveCard');
      Route::delete('/{cardID}', 'deleteCard')
        ->whereNumber('cardID');
      Route::get('/{cardID}/versions', 'getVersions')
        ->whereNumber('cardID');
      Route::get('/{cardID}/archive/{archiveID}', 'getArchiveCard')
        ->whereNumber('cardID')
        ->whereNumber('archiveID');
      Route::put('/{cardID}/status/{statusID}', 'updateCardStatus')
        ->whereNumber('cardID')
        ->whereNumber('statusID');
    });
  });

  Route::controller(OperationsController::class)->group(function () {

    Route::prefix('/operations')->group(function () {
      Route::post('/duplicate', 'duplicateOperationToCards');
      Route::delete('/{operationID}', 'deleteOperation')
        ->whereNumber('operationID');
    });
  });

  Route::controller(StatusController::class)->group(function () {
    Route::prefix('/status')->group(function () {
      Route::get('/', 'getStatuses');
    });
  });

  Route::controller(DocumentsController::class)->group(function () {
    Route::prefix('/documents')->group(function () {
      Route::get('/{cardID}', 'getDocument')
        ->whereNumber('cardID');
      Route::post('/', 'createDocument');
    });
  });
});

// Доступ для других ролей:
// Route::middleware("authApi:$roleA,$roleB")->group(function () {

Route::controller(SearchController::class)->group(function () {
  Route::prefix('/search/')->group(function () {
    Route::post('/cards', 'searchCards');
  });
});

Route::controller(CardsController::class)->group(function () {
  Route::prefix('/cards')->group(function () {
    Route::get('/{cardID}', 'getCard')
      ->whereNumber('cardID');
    Route::post('/show', 'showCards');
  });

  Route::prefix('/operations')->group(function () {
    Route::get('/{cardID}', 'getCard')
      ->whereNumber('cardID');
  });

  Route::prefix('/status')->group(function () {
    Route::get('/get', 'getStatuses');
  });
});

Route::controller(DocumentsController::class)->group(function () {
  Route::prefix('/documents')->group(function () {
    Route::get('/{cardID}', 'getDocument')
      ->whereNumber('cardID');
    Route::post('/', 'createDocument');
  });
});
// });
// });

Route::middleware('auth:sanctum')->get(
  '/user',
  function (Request $request) {
    return $request->user();
  }
);
