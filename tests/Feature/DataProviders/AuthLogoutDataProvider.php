<?php

namespace Tests\Feature\DataProviders;

use Illuminate\Support\Str;

/**
 * ユーザーのログアウト
 *
 * Trait AuthLogoutDataProvider
 * @package Tests\Feature\DataProviders
 */
trait AuthLogoutDataProvider
{
    /**
     * 異常系(アクセストークン)のテストデータの定義
     *
     * @return array
     */
    public function accessTokenProvider()
    {
        return [
            'アクセストークンが無いパターン' => [null],
            'アクセストークンの形式が不正であるパターン' => ['takashi'],
            'アクセストークンが存在しないパターン' => [Str::random(80)],
        ];
    }
}