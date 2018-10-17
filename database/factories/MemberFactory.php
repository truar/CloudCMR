<?php

use Faker\Generator as Faker;

$factory->define(\CloudCMR\Member::class, function (Faker $faker) {
    return [
        'lastname' => $faker->lastName,
        'firstname' => $faker->firstNameMale,
        'email' => $faker->freeEmail,
        'gender' => 'male',
        'birthdate' => $faker->date,
        'uscaNumber' => $faker->randomNumber()
    ];
});

$factory->state(CloudCMR\Member::class, 'space-and-dash', [
    'lastname' => '"Pinchon Carron de la carrier"',
    'firstname' => 'Jean-Paul'
]);
