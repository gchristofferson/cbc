<?php

use Faker\Generator as Faker;

$factory->define(App\Saved::class, function (Faker $faker) {
    return [
        //
        'user_id' => random_int(1, 20),
        'inquiry_id' => random_int(1, 400),
    ];
});
