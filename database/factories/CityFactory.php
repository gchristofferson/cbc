<?php
/*
Create 80 cities with state id numbers between 0 and 10. (Average of 8 each state)
*/
use Faker\Generator as Faker;

$factory->define(App\City::class, function (Faker $faker) {
    return [
        //
        'city' => $faker->city,
        'state_id' => random_int(1,10),
//        'state_id' => function () {
//            return factory(App\State::class)->create()->id;
//        },
    ];
});
