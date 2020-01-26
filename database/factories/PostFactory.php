<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\Post;
use App\Models\User;
use Faker\Generator as Faker;

/**
 * 投稿のダミーデータの生成
 */
$factory->define(Post::class, function (Faker $faker) {
    $imagePath = 'http://placehold.it/300x300';
    $userCount = User::all()->count(); // faker実行回数フルスキャンしてるのでできれば変えたい

    return [
        'user_id' => $faker->numberBetween(1, $userCount),
        'text'    => $faker->text(256),
        'images'  => [ $imagePath, $imagePath, $imagePath, $imagePath, ],
        'liked'   => 0,
    ];
});
