<?php
/*
Create 10 states
*/

use Faker\Generator as Faker;

$factory->define(App\State::class, function (Faker $faker) {
    return [
        //
        'state' => $faker->country,
        'price' => '99.00',
    ];
});
