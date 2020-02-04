<?php

use App\Models\User;
use Carbon\Carbon;
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;

/**
 * ユーザーのダミーデータの生成
 *
 * Class CreateUserSeeder
 */
class CreateUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @param Faker $faker
     */
    public function run(Faker $faker)
    {
        User::truncate();

        // 固定ユーザーの生成
        User::create([
            'user_id'                  => 'menstagram',
            'screen_name'              => 'Menstagram',
            'email'                    => 'system@menstagram.com',
            'avatar'                   => 'http://placehold.it/150x150',
            'biography'                => 'menstagram',
            'password'                 => bcrypt('menstagram'),
            'access_token'             => hash('sha256', 'sQCeW8BEu0OvPULE1phO79gcenQevsamL2TA9yDruTinCAG1yfbNZn9O2udONJgLHH6psVWihISvCCqW'),
            'posted'                   => $faker->numberBetween(1, 999999999),
            'following'                => $faker->numberBetween(1, 9999999999),
            'followed'                 => $faker->numberBetween(1, 9999999999),
            'access_token_deadline_at' => Carbon::now(),
        ]);

        // ランダムにユーザーを生成
        factory(User::class, 10)->create();

        // 画像の削除
        $this->deleteDirectory('public/avatars');

        // 画像の保存
        $files = Storage::files('seeds/avatars');
        $path = 'public/avatars/';
        $filePaths = $this->getPublicPaths($files, $path);

        // 画像パスをavatarに設定
        $this->setImagesPaths(User::all(), $filePaths);
    }

    /**
     * 画像ディレクトリ内の削除
     */
    protected function deleteDirectory($path) {
        $deleteFiles = Storage::files($path);
        foreach ($deleteFiles as $key => $file) {
            if ($file == $path.'/.gitkeep') {
                continue; 
            }
            Storage::delete($file);
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
     * ファイルをコピーして公開パスをリスト化
     */
    protected function getPublicPaths($files, $localStorage) {
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
    protected function setImagesPaths($users, $filePaths)
    {
        foreach ($users as $key => $user) {
            $user->avatar =  $filePaths->random();
            $user->save();
        }
    }
}
