<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('admin.home');
    $router->resource('phorons/users', UserController::class);
    $router->resource('profiles', ProfileController::class);
    $router->resource('dishes', DishController::class);
    $router->resource('faqs', FaqController::class);
});
