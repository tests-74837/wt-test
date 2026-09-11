<?php

Route::post('/imports', [\App\Http\Controllers\Api\Import\CreateController::class, '__invoke']);
Route::get('/imports/{id}', [\App\Http\Controllers\Api\Import\ViewController::class, '__invoke']);

Route::get('/properties', [\App\Http\Controllers\Api\Properties\SearchController::class, '__invoke']);
Route::post('/offers/{id}/reservations', [\App\Http\Controllers\Api\Offers\Reservations\CreateController::class, '__invoke'])
    ->where('id', '\d+');
