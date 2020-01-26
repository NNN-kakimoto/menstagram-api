<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Like;
use App\Models\User;
use App\Models\Post;
use Faker\Generator as Faker;

/**
 * いいねのダミーデータの生成
 */
$factory->define(Like::class, function (Faker $faker) {
    $userCount = User::all()->count();
    $userId = $faker->numberBetween(1, $userCount);
    $postId = $faker->numberBetween(1, Post::all()->count());
    $like = Like::where('user_id', $userId)->where('post_id', $postId)->first();
    while (collect($like)->isNotEmpty()) {
        $postId = $faker->numberBetween(1, $userCount);
        $like = Like::where('user_id', $userId)->where('post_id', $postId)->first();
    }

    return [
        'user_id' => $userId,
        'post_id' => $postId,
    ];
});
