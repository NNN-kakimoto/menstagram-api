<?php

use Illuminate\Database\Seeder;
use App\Models\Like;
use App\Models\Post;

/**
 * いいねのダミーデータの生成
 *
 * Class CreateLikesSeeder
 */
class CreateLikesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Like::truncate();

        Like::create([
            'user_id' => 1,
            'post_id' => 1,
        ]);

        factory(Like::class, 100)->create();

        // 生成したいいねカウントを反映
        $posts = Post::all();
        foreach($posts as $post){
            $post->liked = Like::where('post_id', $post->id)->count();
            $post->save();
        }
    }
}
