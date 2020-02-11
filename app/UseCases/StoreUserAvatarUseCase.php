<?php

namespace App\UseCases;

use Intervention\Image\Facades\Image;

class StoreUserAvatarUseCase
{
    /**
     * アバターの圧縮・保存とユーザーアバターのパス更新
     * @param $request
     */
    public function __invoke()
    {
        // 画像トリミング

        // 画像をstorageに保存

        // 過去のユーザーアバター画像を削除

        // 画像パスをユーザーデータに反映
        return '{}';
    }
}