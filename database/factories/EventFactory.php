<?php

use Faker\Generator as Faker;

$factory->define(\CloudCMR\Event::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'startDate' => $faker->datetime->format('Y-m-d H:i:s'),
        'type' => 'SORTIE',
        'price' => $faker->numberBetween(10, 100)
        //'name' => 'TOTO',
        //'startDate' => '1992-11-21 10:11:01',
        //'type' => 'SORTIE',
        //'price' => 10
    ];
});
