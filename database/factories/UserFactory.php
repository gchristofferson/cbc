<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.

factory('App\User', numRecords)->make(); //makes without persisting
factory('App\User', 20)->create(); //makes and persisting
create 20
|
*/

$factory->define(App\User::class, function (Faker $faker) {
    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'remember_token' => str_random(10),
        'license' => $faker->numberBetween(10000,99999),
        'company_name' => $faker->company,
        'company_website' => $faker->url,
        'main_market' => $faker->city,
        'phone_number' => $faker->phoneNumber,
        'agreed' => 'on',
    ];
});
