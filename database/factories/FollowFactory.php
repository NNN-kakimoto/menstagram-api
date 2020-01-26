<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Follow;
use App\Models\User;
use Faker\Generator as Faker;

/**
 * フォローのダミーデータの生成
 */
$factory->define(Follow::class, function (Faker $faker) {
    $userCount = User::all()->count();

    $userId = $faker->numberBetween(1, $userCount);
    $targetUserId = $faker->numberBetween(1, $userCount);
    for (;;) {
        if ($userId !== $targetUserId) break;
        $targetUserId = $faker->numberBetween(1, $userCount);
    }

    return [
        'user_id'        => $userId,
        'target_user_id' => $targetUserId,
    ];
});
