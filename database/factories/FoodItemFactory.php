<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\FoodItem;
use Faker\Generator as Faker;

$factory->define(FoodItem::class, function (Faker $faker) {
    return [
        //
        "name" => $faker->word,
        "detail" => $faker->paragraph,
        "slug"=>get_unique_dish_slug($faker->word),
        "price" => $faker->numberBetween(100,1000),
        "dish_type" => 'non-veg',
        "cuisine_type" => 'Indian',
        "user_id" => function(){
        	return \App\User::all()->random();
        }
    ];
});
