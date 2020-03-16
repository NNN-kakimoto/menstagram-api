<?php

/**
 * エラーメッセージ
 */

return [
    'user' => [
        'user_id' => [
            'required' => 'ユーザーIDは必須項目です。',
            'regex'    => 'ユーザーIDは半角英数字とアンダーバーのみ使用可能です。',
            'between'  => 'ユーザーIDは1〜16文字の範囲で指定してください。',
            'unique'   => '指定したユーザーIDはすでに存在しています。',
            'exists'   => '指定したユーザーIDは存在しません。',
        ],
        'password' => [
            'required' => 'パスワードは必須項目です。',
            'string'   => 'パスワードは文字列のみ使用可能です。',
            'min'      => 'パスワードは8文字以上で指定してください。',
        ],
        'user_name' => [
            'required' => 'ユーザーネームは必須項目です。',
            'string'   => 'ユーザーネームは文字列のみ使用可能です。',
            'between'  => 'ユーザーネームは1〜16文字の範囲で指定してください。',
        ],
        'email' => [
            'required' => 'メールアドレスは必須項目です。',
            'email'    => 'メールアドレスの形式で指定してください。',
            'unique'   => '指定したメールアドレスはすでに登録されています。',
        ],
        'avatar' => [
            'image'      => '画像でないファイルは選択できません。',
            'max'        => '画像のサイズは5MBが上限です。',
            'not_exists' => '画像の保存に失敗しました。',
        ],
        'biography' => [
            'max' => '自己紹介は128文字以下で指定してください。',
        ],
        'access_token' => [
            'format'     => '認証情報の形式が不正です。',
            'not_exists' => '不正な認証情報です。',
            'deadline'   => '認証情報の期限が切れています。',
        ],
        'not_exists' => 'ユーザーIDかパスワードに誤りがあります。',
    ],

    'slurp' => [
        'id' => [
            'required' => 'スラープIDは必須項目です。',
            'integer'  => 'スラープIDは数値のみ使用可能です。',
            'exists'   => '存在しないスラープIDです。',
        ],
        'image' => [
            'image' => '画像でないファイルは選択できません。',
            'max'   => '画像のサイズは5MBが上限です。',
        ],
        'text' => [
            'required' => 'テキストは必須項目です。',
            'string'   => 'テキストは文字列のみ使用可能です。',
            'between'  => 'テキストは1〜256文字の範囲で指定してください。',
        ],
        'forbid'        => 'スラープの書き込み権限がありません。',
        'already'       => 'すでにヤムをしているスラープです。',
        'yet'           => 'まだヤムをしていないスラープです。',
        'cannot_delete' => '削除できないスラープです。'
    ],

    'follow' => [
        'id' => [
            'integer' => 'フォローIDは数値のみ使用可能です。',
            'exists'  => '指定したフォローIDは存在しません。',
        ],
        'target_user_id' => [
            'required' => 'ユーザーIDは必須項目です。',
            'regex'    => 'ユーザーIDは半角英数字とアンダーバーのみ使用可能です。',
            'between'  => 'ユーザーIDは1〜16文字の範囲で指定してください。',
            'exists'   => '指定したユーザーIDは存在しません。',
        ],
        'forbid' => '不正なフォロー操作です。',
    ],

    'general' => [
        'type' => [
            'in' => '存在しないタイプです。',
        ],
    ],
];
