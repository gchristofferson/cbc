<?php

/*
user_id between 1 and 20. state_id between 1 and 10. Create 40 (average 2 per user)
*/

use Faker\Generator as Faker;

$factory->define(App\Subscription::class, function (Faker $faker) {
    return [
        'user_id' => random_int(1, 20),
        'state_id' => random_int(1, 10),
        'discount_id' => random_int(1, 20),
        'has_discount' => $faker->boolean(50),
        'discount_expire_date' => now()->addDays(random_int(30, 90)),
        'discount_expired' => false,
        'used' => 1,
        'subscription_start_date' => now(),
        'subscription_expire_date' => now()->addDays(365),
        //
//        'user_id' => function () {
//            return factory(App\User::class)->create()->id;
//        },
//        'state_id' => function () {
//            return factory(App\State::class)->create()->id;
//        },
    ];
});
