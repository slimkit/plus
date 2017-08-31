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
|
*/

$factory->define(Zhiyi\Plus\Models\User::class, function (Faker $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'phone' => $faker->unique()->phoneNumber,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(Zhiyi\Plus\Models\AuthToken::class, function (Faker $faker) {
    return [
        'token' => str_random(10),
        'refresh_token' => str_random(10),
        'user_id' => null,
        'expires' => 0,
        'state' => 1,
    ];
});

$factory->define(Zhiyi\Plus\Models\VerificationCode::class, function (Faker $faker) {
    return [
        'user_id' => null,
        'channel' => 'mail',
        'account' => $faker->safeEmail,
        'code' => $faker->numberBetween(1000, 999999),
        'state' => 0,
    ];
});
