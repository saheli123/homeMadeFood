<?php

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

Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', 'API\RegisterController@login')->name('login');
    Route::post('register', 'API\RegisterController@register');
    Route::group([
      'middleware' => 'auth:api'
    ], function() {
        Route::get('logout', 'API\RegisterController@logout');
        Route::get('user', 'API\RegisterController@user');
    });
});
Route::apiResource('/products','FoodItemController');

Route::get('/cooks/{search?}','UserController@getCooks');
Route::get('/searchFood/{food?}','FoodItemController@searchFood');
Route::group(['prefix' => 'products'],function(){

  Route::apiResource('/{product}/reviews','ReviewController');

});
