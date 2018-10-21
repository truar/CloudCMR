<?php

use Faker\Generator as Faker;

$factory->define(\Lecturize\Addresses\Models\Address::class, function (Faker $faker) {
    return [
        'street'     => $faker->streetAddress,
        'city'       => $faker->city,
        'post_code'  => $faker->postcode,
        'country'    => 'FRA', // ISO-3166-2 or ISO-3166-3 country code
        //'is_primary' => true, // optional flag
    ];
});
