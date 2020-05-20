<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Contact;
use Faker\Generator as Faker;

$factory->define(Contact::class, function (Faker $faker) {
    $lat=$faker->latitude();
    $lng=$faker->longitude();
    $address=\App\User::getLocationFromLatLng($lat,$lng);
    return [
        //

            "country" =>$address["country"],
            "state" => $address["state"],
            "city"=> $address["city"],
            "phone" => $faker->numberBetween(500000,8000000),
            "pincode" => $faker->numberBetween(500000,800000),
            "address_line_1"=>$faker->address,
            "latitude" =>$lat,
            "longitude"=>$lng

    ];
});
