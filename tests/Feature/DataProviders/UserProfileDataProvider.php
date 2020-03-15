<?php

namespace Tests\Feature\DataProviders;

/**
 * ユーザーのプロフィール
 *
 * Trait UserProfileDataProvider
 * @package Tests\Feature\DataProviders
 */
trait UserProfileDataProvider
{
    /**
     * 異常系(ユーザーID)
     *
     * @return array
     */
    public function userIdProvider()
    {
        return [
            'ユーザーIDが抜けているパターン' => [null],
            'ユーザーIDが空文字のパターン' => [''],
            'ユーザーIDがa-zA-Z0-9の範囲に無いパターン' => ['めんすたぐらむ'],
            'ユーザーIDが16文字を超えているパターン' => ['menstagraaaaaaaam'], // 17文字
        ];
    }

    /**
     * 異常系(ユーザーIDが存在しない)
     *
     * @return array
     */
    public function userIdNotFoundProvider()
    {
        return [
            'ユーザーIDの条件は満たしているが存在しないユーザーIDのパターン' => ['takashi'],
        ];
    }
}