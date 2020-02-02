<?php

use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\User;

use Intervention\Image\Facades\Image;
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

        // 画像の削除
        $deleteFiles = Storage::files('public/posts');
        foreach($deleteFiles as $key => $file){
            if($file == 'public/posts/.gitkeep') { continue; }
            Storage::delete($file);
        }

        // 画像の保存
        $files = Storage::files('initial/ramens');
        $path = 'public/posts/';

        $filePaths = collect([]);

        foreach ($files as $key => $file) {
            //リサイズ とりあえず初期データごとリサイズして上書き保存している
            $oldFilePath = storage_path("app/$file");
            $newFileName = $this->getFileName($file);
            $image = Image::make(file_get_contents($oldFilePath));
            $this->trimImage($image)->save($oldFilePath);
            Storage::copy($file, $path.$newFileName);
            $filePaths->push(asset("storage/posts/$newFileName"));
        }

        // 画像パスを投稿に設定
        $posts = Post::all();
        foreach($posts as $key => $post){
            $fileCount = rand(1, 4);  // スライダーを使うために複数個指定
            $images = [];
            for($i=1; $i<= $fileCount; $i++){
                $images[] = $filePaths->random();
            }
            $post->images = $images;
            $post->save();
        }
    }

     /**
     * ファイルネームの取得
     *
     * @param $file
     * @return string
     */
    protected function getFileName($file)
    {
        $extension = \File::extension($file);
        $fileName = Str::random(16) . ".$extension";
        return $fileName;
    }

     /**
     * 画像のトリミング
     *
     * @param $image
     * @return mixed
     */
    protected function trimImage($image)
    {
        if ($image->width() > $image->height()) {
            return $image->resize(1024, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }
        return $image->resize(null, 1024, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
    }
}
