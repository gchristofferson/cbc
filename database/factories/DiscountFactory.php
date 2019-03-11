<?php
/*
Create 20 discounts with state id numbers between 0 and 10. (Average of 2 each state)
*/

use Faker\Generator as Faker;

$factory->define(App\Discount::class, function (Faker $faker) {
    return [
        //
        'state_id' => random_int(1,10),
        'discount' => strval(random_int(1,99)),
        'discount_desc' => $faker->sentence,
        'days_to_expire_discount' => strval(random_int(30, 365)),
        'discount_limit' => '1',
        'promo_code' => $faker->word,
    ];
});
