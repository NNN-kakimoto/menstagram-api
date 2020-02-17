<?php

namespace App\UseCases;

use App\Models\User;

use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Storage;

class StoreUserAvatarUseCase
{
    /**
     * アバターの圧縮・保存とユーザーアバターのパス更新
     * @param $request
     * @return String response
     */
    public function __invoke ($request)
    {
        // 画像トリミング
        $file = $this->getImage($request);
        $image = Image::make($file);
        $image = $this->trimImage($image)->encode('jpg');

        //保存
        $publicFilePath = $this->storeImage($image);
        
        // 過去のユーザーアバター画像を削除
        $user = User::where('id', user()->id)->first();
        $this->deleteOldAvatar($user);

        // 画像パスをユーザーデータに反映
        $user->avatar = $publicFilePath;
        $user->save();

        return '{}';
    }

    /**
     * 画像の取得 (無かったら400)
     * @param $request
     * @return file
     */
    protected function getImage ($request)
    {
        $file = $request->file("avatar");
        if (null === $file ) {
            $response = response('{}', 400);
            throw new HttpResponseException($response);
        }
        return $file;
    }

     /**
     * 画像のトリミング
     *
     * @param $image
     * @return mixed
     */
    protected function trimImage ($image)
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

    /**
     * 画像の保存
     * @param $image
     * @return String public path
     */
    protected function storeImage ($image)
    {
        // // 画像をstorageに保存
        // $image = Image::make($images[$i]);
        $fileName = Str::random(16) . '.jpg';
        $storageFilePath = storage_path("app/public/avatars/custom/$fileName");
        $image->save($storageFilePath);
        $publicFilePath = asset("avatars/custom/$fileName");
        return $publicFilePath;
    }

    /**
     * ユーザーの元アバターを削除
     * @param User $user
     * @return void
     */
    function deleteOldAvatar ($user)
    {
        $nowAvatar = $user->avatar;
        $paths = explode('/', $nowAvatar);
        if ('default' !== $paths[count($paths) -2]) {
            Storage::delete('/public/avatars/custom/'. $paths[count($paths) - 1] );
        }
    }
}
