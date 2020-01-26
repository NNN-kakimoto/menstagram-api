<?php

use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\User;

/**
 * 投稿のダミーデータの生成
 *
 * Class CreatePostSeeder
 */
class CreatePostsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Post::truncate();
        factory(Post::class, 100)->create();

        // ユーザー投稿数の整合性
        $users = User::all();
        foreach($users as $user){
            $user->posted = Post::where('user_id', $user->id)->count();
            $user->save();
        }
    }
}
