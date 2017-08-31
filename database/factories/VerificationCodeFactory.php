<?php

use Faker\Generator as Faker;

$factory->define(Zhiyi\Plus\Models\VerificationCode::class, function (Faker $faker) {
    return [
        'user_id' => null,
        'channel' => 'mail',
        'account' => $faker->safeEmail,
        'code' => $faker->numberBetween(1000, 999999),
        'state' => 0,
    ];
});
