<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Zhiyi\Plus\Models\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'phone' => $faker->unique()->phoneNumber,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(Zhiyi\Plus\Models\AuthToken::class, function (Faker\Generator $faker) {
    return [
        'token' => str_random(10),
        'refresh_token' => str_random(10),
        'user_id' => null,
        'expires' => 0,
        'state' => 1,
    ];
});

$factory->define(Zhiyi\Plus\Models\StorageTask::class, function (Faker\Generator $faker) {
    return [
        'hash' => $faker->md5,
        'origin_filename' => 'origin.png',
        'filename' => 'origin.png',
        'width' => null,
        'height' => null,
        'mime_type' => 'image/png',
    ];
});

$factory->define(Zhiyi\Plus\Models\Storage::class, function (Faker\Generator $faker) {
    return [
        'origin_filename' => 'origin.png',
        'filename' => 'origin.png',
        'hash' => $faker->md5,
        'mime' => 'image/png',
        'extension' => 'png',
        'image_width' => null,
        'image_height' => null,
    ];
});

$factory->define(Zhiyi\Plus\Models\VerifyCode::class, function (Faker\Generator $faker) {
    return [
        'account' => $faker->unique()->phoneNumber,
        'code' => $faker->numberBetween(1000, 999999),
        'state' => 0,
    ];
});
