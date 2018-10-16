<?php

use Faker\Generator as Faker;

$factory->define(\App\Transportation::class, function (Faker $faker) {
    return [
        'type' => 'BUS',
        'departureDate' => $faker->datetime->format('Y-m-d H:i:s'),
        'arrivalDate' => $faker->datetime->format('Y-m-d H:i:s'),
        'departureLocation' => $faker->address,
        'arrivalLocation' => $faker->address
    ];
});
