<?php

use Faker\Generator as Faker;

$factory->define(App\Notification::class, function (Faker $faker) {
    return [
        //
        'user_id' => random_int(1, 20),
        'city_id' => random_int(1, 80),
    ];
});
