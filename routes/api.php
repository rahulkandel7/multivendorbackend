<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Routes using prefix v1
Route::prefix('v1/')->group(function () {
    // ROutes without auth
    Route::post('/register', 'App\Http\Controllers\Api\v1\AuthController@register');
    Route::post('/login', 'App\Http\Controllers\Api\v1\AuthController@login');

    //Routes with auth
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', 'App\Http\Controllers\Api\v1\AuthController@logout');
        Route::prefix('admin')->group(function () {

            Route::apiResource('/categories', 'App\Http\Controllers\Api\v1\CategoryController');
            Route::apiResource('/sub-categories', 'App\Http\Controllers\Api\v1\SubCategoryController');
            Route::apiResource('/slideshows', 'App\Http\Controllers\Api\v1\SlideshowController');
        });
    });
});
