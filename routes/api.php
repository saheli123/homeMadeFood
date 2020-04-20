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

Route::get('email/verify/{id}', 'API\VerificationApiController@verify')->name('verificationapi.verify');
Route::get('email/resend', 'API\VerificationApiController@resend')->name('verificationapi.resend');
Route::group([

    'middleware' => 'api',
    'prefix' => 'password'
], function () {
    Route::post('create', 'API\PasswordResetController@create');
    Route::get('find/{token}', 'API\PasswordResetController@find');
    Route::post('reset', 'API\PasswordResetController@reset');
});
Route::group([
    'prefix' => 'auth'
], function () {

    Route::post('login', 'API\RegisterController@login')->name('login');
    Route::post('register', 'API\RegisterController@register');
    Route::group([
        'middleware' => 'auth:api'
    ], function () {

        Route::get('logout', 'API\RegisterController@logout');
        Route::get('user', 'API\RegisterController@user')->middleware('verified');
    });
});



Route::group([
    'middleware' => ['auth:api','verified']
], function () {
    Route::post("/uploadPicture", "ProfileController@uploadProfilePicture");
    Route::get('/profile/{user_id}', 'UserController@GetProfileData');
    Route::post('/updateContact', 'UserController@updateContact');
    Route::post('/resetPassword', 'UserController@updatePassword');
    Route::post('/updateProfile', 'UserController@updateProfile');

    Route::post("/saveCart", "CartController@addToCart");
    Route::get("/getCart/{userId}", "CartController@getCart");
    Route::apiResource('/checkout', 'OrderController');
    Route::get("/orders/{userId}", "OrderController@getOrdersByCustomer");
    Route::get("/ordersByCook/{userId}", "OrderController@getOrdersByCook");
    Route::get("/ordersTotal/{type}/{userId}", "OrderController@totalOrders");

    Route::get("/orderDetails/{orderId}", "OrderController@getOrderDetailsByOrderId");

    Route::post("/markasreadnotification", "UserController@setMarkAsReadNotification");
});

Route::get('/cooks/{search?}', 'UserController@getCooks');
Route::get('showDish/{dishId}', 'FoodItemController@showDish');
Route::get('/cookDetails/{id}', 'UserController@getCookById');
//Route::get('/dishes/{cookId}','FoodItemController@getDishes');

Route::get('/searchFood/{food?}', 'FoodItemController@searchFood');
Route::apiResource('/dishes', 'FoodItemController');


//Route::apiResource('/cooks', 'UserController');

// Route::group(['prefix' => 'products'],function(){

//   Route::apiResource('/{product}/reviews','ReviewController');

// });
