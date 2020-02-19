<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\FoodItem;
use Faker\Generator as Faker;

$factory->define(FoodItem::class, function (Faker $faker) {
    return [
        //
        "name" => $faker->word,
        "detail" => $faker->paragraph,
        "price" => $faker->numberBetween(100,1000),
        "delivery_type" => 'pickup',
        "user_id" => function(){
        	return \App\User::all()->random();
        }
    ];
});
