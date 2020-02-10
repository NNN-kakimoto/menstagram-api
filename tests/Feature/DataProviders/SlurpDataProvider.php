<?php

namespace Tests\Feature\DataProviders;

use Illuminate\Http\UploadedFile;

/**
 * 画像の送信
 *
 * Trait SlurpDataProvider
 * @package Tests\Feature\DataProviders
 */
trait SlurpDataProvider
{
    /**
     * 異常系(画像)のテストデータの定義
     *
     * @return array
     */
    public function imagesProvider()
    {
        return [
            '画像が含まれていないパターン' => [null],
            '画像ではないパターン' => ['takashi'],
            '画像サイズが上限を超えているパターン' => [UploadedFile::fake()->image('test.jpg', 100, 100)->size(5000)],
        ];
    }
}