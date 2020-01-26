<?php

use Illuminate\Database\Seeder;
use App\Models\Follow;
use App\Models\User;

/**
 * フォローのダミーデータの生成
 *
 * Class CreateFollowsSeeder
 */
class CreateFollowsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Follow::truncate();
        factory(Follow::class, 100)->create();

        // フォロー数の整合性
        $users = User::all();
        foreach($users as $user){
            $user->following = Follow::where('user_id', $user->id)->count();
            $user->followed = Follow::where('target_user_id', $user->id)->count();
            $user->save();
        }
    }
}
