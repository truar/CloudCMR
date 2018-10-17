<?php

use Faker\Generator as Faker;

$factory->define(\CloudCMR\Phone::class, function (Faker $faker) {
    return [
        'number' => $faker->phoneNumber
    ];
});
