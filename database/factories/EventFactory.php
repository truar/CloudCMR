<?php

use Faker\Generator as Faker;

$factory->define(\App\Event::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'startDate' => $faker->datetime->format('Y-m-d h:i:s'),
        'type' => 'SORTIE',
        'price' => $faker->randomNumber()
    ];
});
