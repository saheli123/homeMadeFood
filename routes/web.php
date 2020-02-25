<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::apiResource('/products','FoodItemController');

Route::get('/cooks/{search?}','UserController@getCooks');
Route::get('/cookDetails/{id}','UserController@getCookById');
Route::get('/dishes/{cookId}','FoodItemController@getDishes');

Route::get('/searchFood/{food?}','FoodItemController@searchFood');
Route::group(['prefix' => 'products'],function(){

  Route::apiResource('/{product}/reviews','ReviewController');

});
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
