<?php

use App\Http\Controllers\ItemsController;
use Illuminate\Support\Facades\Route;

Route::get('/res/{ulid}', [ItemsController::class, 'publicPost'])->name('recipe.publicPost');
