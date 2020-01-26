<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\User;
use Faker\Generator as Faker;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * ユーザーのダミーデータの生成
 */
$factory->define(User::class, function (Faker $faker) {

    $userId = Str::random(10);
    $email = $faker->email;
    $user = User::where('user_id', $userId)->where('email', $email)->first();
    while (collect($user)->isNotEmpty()) {
        $userId = Str::random(10);
        $email = $faker->email;
        $user = User::where('user_id', $userId)->where('email', $email)->first();
    }

    $accessToken = Arr::random([ null, hash('sha256', Str::random(80)), ]);

    return [
        'user_id'                  => $userId,
        'screen_name'              => $faker->firstName,
        'email'                    => $email,
        'avatar'                   => 'http://placehold.it/150x150',
        'biography'                => $faker->text(128),
        'password'                 => bcrypt('menstagram'),
        'access_token'             => $accessToken,
        'posted'                   => 0,
        'following'                => 0,
        'followed'                 => 0,
        'access_token_deadline_at' => $accessToken ? $faker->dateTimeThisDecade : null,
    ];
});
