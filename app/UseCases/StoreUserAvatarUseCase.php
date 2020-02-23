<?php

namespace App\UseCases;

use App\Models\User;

use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

/**
 * ユーザーのアバターの保存
 *
 * Class StoreUserAvatarUseCase
 * @package App\UseCases
 */
class StoreUserAvatarUseCase
{
    /**
     * @param $request
     * @return string
     */
    public function __invoke($request)
    {
        $file = $request->file('avatar');
        if (is_null($file)) return false;
        $image = Image::make($file);

        $image = $this->trimImage($image)->encode('jpg');

        $publicFilePath = $this->storeImage($image);
        
        $user = User::where('id', user()->id)->first();
        $this->deleteOldAvatar($user->avatar);

        $user->avatar = $publicFilePath;
        $user->save();

        return true;
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

    /**
     * 新しいアバターの保存
     *
     * @param $image
     * @return string
     */
    protected function storeImage($image)
    {
        $fileName = Str::random(16) . '.jpg';
        $storageFilePath = storage_path("app/public/avatars/custom/$fileName");
        $image->save($storageFilePath);
        $publicFilePath = asset("avatars/custom/$fileName");
        return $publicFilePath;
    }

    /**
     * 元のアバターを削除
     *
     * @param $avatar
     */
    protected function deleteOldAvatar($avatar)
    {
        $paths = explode('/', $avatar);
        if ($paths[count($paths) -2] !== 'default') {
            Storage::delete('/public/avatars/custom/'. $paths[count($paths) - 1] );
        }
    }
}
