<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Contact;
use Faker\Generator as Faker;

$factory->define(Contact::class, function (Faker $faker) {
    return [
        //

            "country" => function(){
                return \App\Countries::all()->random();
            },
            "state" => function(){
                return \App\States::all()->random();
            },
            "city"=> function(){
                return \App\Cities::all()->random();
            },
            "phone" => $faker->numberBetween(500000,8000000),
            "pincode" => $faker->numberBetween(500000,800000),
            "address_line_1"=>$faker->name

    ];
});
