<?php

namespace Tests\Feature\DataProviders;

/**
 * フォロー
 *
 * Trait UserPostFollowDataProvider
 * @package Tests\Feature\DataProviders
 */
trait UserPostFollowDataProvider
{
    /**
     * 異常系(フォロー対象のユーザーID)のテストデータの定義
     *
     * @return array
     */
    public function targetUserIdProvider()
    {
        return [
            'フォロー対象のユーザーIDが抜けているパターン' => [null],
            'フォロー対象のユーザーIDが空文字のパターン' => [''],
            'フォロー対象のユーザーIDがa-zA-Z0-9の範囲に無いパターン' => ['めんすたぐらむ'],
            'フォロー対象のユーザーIDが16文字を超えているパターン' => ['menstagraaaaaaaam'], // 17文字
            'フォロー対象のユーザーIDが存在しないパターン' => ['hoge'],
        ];
    }
}