<?php

namespace Tests\Feature\DataProviders;

/**
 * フォロイー一覧
 *
 * Trait UserFolloweeDataProvider
 * @package Tests\Feature\DataProviders
 */
trait UserFolloweeDataProvider
{
    /**
     * 正常系(type)のテストデータの定義
     *
     * @return array
     */
    public function successTypeProvider()
    {
        return [
            'typeがoldのパターン' => ['old'],
            'typeがnewのパターン' => ['new'],
        ];
    }

    /**
     * 異常系(ユーザーID)のテストデータの定義
     *
     * @return array
     */
    public function failUserIdProvider()
    {
        return [
            'ユーザーIDが空文字のパターン' => [''],
            'ユーザーIDがa-zA-Z0-9の範囲に無いパターン' => ['めんすたぐらむ'],
            'ユーザーIDが16文字を超えているパターン' => ['menstagraaaaaaaam'], // 17文字
            'ユーザーIDの条件は満たしているが存在しないユーザーIDのパターン' => ['takashi'],
        ];
    }

    /**
     * 異常系(フォローID)のテストデータの定義
     *
     * @return array
     */
    public function failFollowIdProvider()
    {
        return [
            'follow_idが文字列のパターン' => ['takashi'],
            'follow_idが存在しないパターン' => [999],
        ];
    }

    /**
     * 異常系(type)のテストデータの定義
     *
     * @return array
     */
    public function failTypeProvider()
    {
        return [
            'typeが存在しないパターン' => ['takashi'],
        ];
    }
}