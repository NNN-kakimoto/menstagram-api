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
        foreach ($users as $user) {
            $user->posted = Post::where('user_id', $user->id)->count();
            $user->save();
        }

        // 画像の削除
        $this->directoryDelete('public/posts');

        // 画像の保存
        $files = Storage::files('seeds/posts');
        $path = 'public/posts/';
        $filePaths = $this->getPublicPaths($files, $path);

        // 画像パスを投稿に設定
        $this->setImagesPaths(Post::all(), $filePaths);
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
     * 画像ディレクトリ内の削除
     */
    protected function directoryDelete($path)
    {
        $deleteFiles = Storage::files($path);
        foreach ($deleteFiles as $key => $file) {
            if ($file == $path.'/.gitkeep') {
                continue; 
            }
            Storage::delete($file);
        }
    }

    /**
     * ファイルをコピーして公開パスをリスト化
     */
    protected function getPublicPaths($files, $localStorage)
    {
        $filePaths = collect([]);

        foreach ($files as $key => $file) {
            $oldFilePath = storage_path("app/$file");
            $newFileName = $this->getFileName($file);
            Storage::copy($file, $localStorage.$newFileName);
            $publicPath = '/storage/' . explode('/', $localStorage)[1] . '/';
            $filePaths->push(asset( $publicPath . $newFileName));
        }
        return $filePaths;
    }

    /**
     * 配列で受け取ったファイルパスをセット
     */
    protected function setImagesPaths($posts, $filePaths)
    {
        foreach ($posts as $key => $post) {
            $fileCount = rand(1, 4);  // スライダーを使うために複数個指定
            $images = [];
            for ($i=1; $i<= $fileCount; $i++) {
                $images[] = $filePaths->random();
            }
            $post->images = $images;
            $post->save();
        }
    }
}
