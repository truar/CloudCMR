<?php

use Faker\Generator as Faker;

$factory->define(\App\Member::class, function (Faker $faker) {
    return [
        'lastname' => $faker->lastName,
        'firstname' => $faker->firstNameMale,
        'email' => $faker->freeEmail,
        'gender' => 'male',
        'birthdate' => $faker->date
    ];
});

$factory->state(\App\Member::class, 'duplicate', function (Faker $faker) {
    return [
        'lastname' => 'last',
        'firstname' => 'first',
        'birthdate' => '1990-05-24'
    ];
});

$factory->state(\App\Member::class, 'empty', function (Faker $faker) {
    return [
        'lastname' => '',
        'firstname' => '',
        'birthdate' => '',
        'email' => '',
        'gender' => ''
    ];
});