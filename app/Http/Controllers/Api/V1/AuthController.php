<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthLoginRequest;
use App\Http\Requests\AuthRegisterRequest;
use App\UseCases\LoginUserUseCase;
use App\UseCases\LogoutUserUseCase;
use App\UseCases\RegisterUserUseCase;
use App\UseCases\ExistsUserUseCase;

/**
 * 認証系API
 *
 * Class AuthController
 * @package App\Http\Controllers\Api\V1
 */
class AuthController extends Controller
{
    /**
     * ユーザーの登録
     *
     * @param AuthRegisterRequest $request
     * @param RegisterUserUseCase $useCase
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function register(AuthRegisterRequest $request, RegisterUserUseCase $useCase)
    {
        $accessToken = $useCase($request->user_id, $request->user_name, $request->email, $request->password);
        return response(['access_token' => $accessToken], 200);
    }

    /**
     * ユーザーのログイン
     *
     * @param AuthLoginRequest $request
     * @param ExistsUserUseCase $existsUserUseCase
     * @param LoginUserUseCase $loginUserUseCase
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|void
     */
    public function login(AuthLoginRequest $request, ExistsUserUseCase $existsUserUseCase, LoginUserUseCase $loginUserUseCase)
    {
        if (!$existsUserUseCase($request->user_id, $request->password)) return err_response(['message' => config('errors.user.not_exists')], 400);

        $accessToken = $loginUserUseCase($request->user_id);
        return response(['access_token' => $accessToken], 200);
    }

    /**
     * ユーザーのログアウト
     *
     * @param LogoutUserUseCase $useCase
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function logout(LogoutUserUseCase $useCase)
    {
        $useCase();
        return response('{}', 200);
    }
}
