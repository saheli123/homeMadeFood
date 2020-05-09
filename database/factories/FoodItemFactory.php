<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\FoodItem;
use Faker\Generator as Faker;
//'name','delivery_time','delivery_end_time','delivery_type','picture', 'detail','slug', 'dish_type','cuisine_type','price',"unit",'user_id'
$factory->define(FoodItem::class, function (Faker $faker) { 
    $startingDate = $faker->dateTimeBetween('this week', '+6 days');
    // Random datetime of the current week *after* `$startingDate`
    $endingDate   = $faker->dateTimeBetween($startingDate, strtotime('+6 days'));

    return [
        //
        "name" => $faker->word,
        "detail" => $faker->paragraph,
        "delivery_time"=>$startingDate,
        "delivery_end_time"=>$endingDate,
        "delivery_type"=>"Pickup",
        "picture"=>"",
        "slug"=>get_unique_dish_slug($faker->word),
        "price" => $faker->numberBetween(100,1000),
        "unit"=>"plate",
        "dish_type" => 'non-veg',
        "cuisine_type" => 'Indian',
        "user_id" => function(){
        	return \App\User::all()->random();
        }
    ];
});
