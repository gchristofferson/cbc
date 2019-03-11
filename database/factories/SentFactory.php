<?php

use Faker\Generator as Faker;

$factory->define(App\Sent::class, function (Faker $faker) {
    return [
        //
        'user_id' => random_int(1, 20),
        'inquiry_id' => random_int(1, 400),
        'city_id' => random_int(1, 80),
    ];
});
