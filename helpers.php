<?php

use Illuminate\Support\Str;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Models\User;

/**
 * アクセストークンの取得
 */
if (!function_exists('access_token')) {
    function access_token() {
        $accessToken = hash('sha256', Str::after(request()->header('Authorization'), 'Bearer '));
        return $accessToken;
    }
}

/**
 * ユーザー情報の取得
 */
if (!function_exists('user')) {
    function user($userId = null) {
        $user = User::query();

        if ($userId) {
            $user->where('user_id', $userId);
        }
        else {
            $user->where('access_token', access_token());
        }

        return $user->first();
    }
}

/**
 * エラー時のレスポンスを生成
 */
if (!function_exists('err_response')) {
    function err_response($content = ['message' => ''], $status = 400) {
        $response['errors'] = $content;
        throw new HttpResponseException(response($response, $status));
    }
}