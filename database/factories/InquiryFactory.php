<?php
/*
Create 400 inquiries with user id numbers between 0 and 20. (Average if 20 each user)
*/

use Faker\Generator as Faker;

$factory->define(App\Inquiry::class, function (Faker $faker) {
    return [
        //
        'subject' => $faker->sentence,
        'body' => $faker->text,
        'sent' => true,
        'user_id' => random_int(1,20),
//        'user_id' => function () {
//            return factory(App\User::class)->create()->id;
//        },
    ];
});
