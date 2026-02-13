<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemsController;
use App\Http\Controllers\Social\PostController;
use Illuminate\Support\Facades\Route;


Route::post('/login', [AuthController::class, 'login']);

Route::post('/google-login', [AuthController::class, 'googleLogin']);
Route::post('/signup', [AuthController::class, 'singUp']);


Route::group(['middleware' => 'auth:sanctum'], function () {

    Route::group(["prefix" => "notifications"], function () {
        Route::get("/", [AuthController::class, 'notifications']);
        Route::get("/count", [AuthController::class, 'notificationCount']);
        Route::post("/mark-as-read", [AuthController::class, 'markNotificationsAsRead']);
    });

    /**
     * user routes
     */
    Route::get('/user', [AuthController::class, "user"]);
    Route::post('/set-user-theme', [AuthController::class, "setUserTheme"]);
    Route::post("update-user", [AuthController::class, "updateUser"]);
    Route::post("change-avatar", [AuthController::class, "changeAvatar"]);
    Route::get('/ably/token', [AuthController::class, 'ablyToken']);

    /**
     * items routes
     */

    Route::get('/items', [ItemsController::class, "index"]);
    Route::get('/user-items', [ItemsController::class, "userItems"]);
    Route::post('/items', [ItemsController::class, "store"]);
    Route::delete('/user-items/{id}', [ItemsController::class, "destroy"]);

    /**
     * recipe routes
     */
    Route::get("/gen-recipes", [ItemsController::class, "getRecipes"]);
    Route::get("/gen-recipes-count", [ItemsController::class, "getDailyRequestCount"]);

    /**
     * recipes routes
     */
    Route::get("/public-recipe/{ulid}", [ItemsController::class, "publicRecipe"]);
    Route::get("/recipes", [ItemsController::class, "recipesToday"]);
    Route::get("/recipes/{id}", [ItemsController::class, "show"]);
    Route::patch("/recipes/{id}/bookmark", [ItemsController::class, "bookmark"]);


    /**
     * bookmark routes
     */
    Route::get("/bookmarks", [ItemsController::class, "bookmarks"]);
    Route::get("/recent-bookmarks", [ItemsController::class, "recentBookmarks"]);


    /**
     * Social routes
     */

    Route::group(["prefix" => "social"], function () {
        Route::post("/post", [PostController::class, "store"]);
        Route::get("/feed", [PostController::class, "generalFeed"]);
        Route::get("/discover", [PostController::class, "discover"]);
        Route::get("/following", [PostController::class, "following"]);
        Route::get("/recommended", [PostController::class, "recommended"]);

        Route::post("/follow", [PostController::class, "follow"]);
        Route::post("/unfollow", [PostController::class, "unfollow"]);
        Route::post("/like", [PostController::class, "like"]);
        Route::post("/unlike", [PostController::class, "unlike"]);

    });

});
