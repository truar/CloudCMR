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
