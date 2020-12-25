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

Route::apiResources([
    'doors' => \App\Http\Controllers\API\DoorController::class,
    'brands' => \App\Http\Controllers\API\BrandController::class,
    'countries' => \App\Http\Controllers\API\CountryController::class,
    'colors' => \App\Http\Controllers\API\ColorController::class,
    'materials' => \App\Http\Controllers\API\MaterialController::class,
    'orders' => \App\Http\Controllers\API\OrderController::class
]);

//Route::get('/get_cart','App\Http\Controllers\API\CartController@get_cart');
Route::post('/add_to_cart','App\Http\Controllers\API\CartController@add_to_cart');
Route::delete('/delete_from_cart/{id}','App\Http\Controllers\API\CartController@delete_from_cart');

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', 'App\Http\Controllers\API\AuthController@login');
    Route::post('logout', 'App\Http\Controllers\API\AuthController@logout');
    Route::post('refresh', 'App\Http\Controllers\API\AuthController@refresh');
    Route::post('me', 'App\Http\Controllers\API\AuthController@me');
    Route::post('register', 'App\Http\Controllers\API\AuthController@register');
});
