<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemsController;
use Illuminate\Support\Facades\Route;


Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/user', [AuthController::class, "user"]);

    /**
     * items routes
     */

    Route::get('/items', [ItemsController::class, "index"]);


});
