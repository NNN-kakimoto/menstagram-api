<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserEditRequest;
use App\Http\Requests\UserFollowerRequest;
use App\Http\Requests\UserPostFollowRequest;
use App\Http\Requests\UserGetFollowRequest;
use App\Http\Requests\UserYumsRequest;
use App\Http\Requests\UserSlurpsRequest;
use App\Http\Requests\UserProfileRequest;
use App\Http\Requests\UserUnfollowRequest;
use App\UseCases\FetchFollowerUseCase;
use App\UseCases\FetchFollowUseCase;
use App\UseCases\FetchUserYumsUseCase;
use App\UseCases\FetchUserSlurpsUseCase;
use App\UseCases\FetchUserProfileUseCase;
use App\UseCases\FollowUseCase;
use App\UseCases\UnfollowUseCase;
use App\UseCases\UpdateUserUseCase;

/**
 * ユーザー系API
 *
 * Class UserController
 * @package App\Http\Controllers\Api\V1
 */
class UserController extends Controller
{
    /**
     * ユーザーのプロフィール
     *
     * @param UserProfileRequest $request
     * @param FetchUserProfileUseCase $useCase
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function profile(UserProfileRequest $request, FetchUserProfileUseCase $useCase)
    {
        $userId = $request->user_id ? user($request->user_id)->id : user()->id;
        $response = $useCase($userId);
        return response($response, 200);
    }

    /**
     * ユーザーのスラープ一覧
     *
     * @param UserSlurpsRequest $request
     * @param FetchUserSlurpsUseCase $useCase
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function slurps(UserSlurpsRequest $request, FetchUserSlurpsUseCase $useCase)
    {
        $response = $useCase($request->user_id, $request->slurp_id, $request->type);
        return response($response, 200);
    }

    /**
     * ユーザーの編集
     *
     * @param UserEditRequest $request
     * @param UpdateUserUseCase $useCase
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function edit(UserEditRequest $request, UpdateUserUseCase $useCase)
    {
        $useCase($request);
        return response('{}', 200);
    }

    /**
     * ヤムしたスラープ一覧
     *
     * @param UserYumsRequest $request
     * @param FetchUserYumsUseCase $useCase
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function yums(UserYumsRequest $request, FetchUserYumsUseCase $useCase)
    {
        $response = $useCase($request->slurp_id, $request->type);
        return response($response, 200);
    }

    /**
     * フォロー
     *
     * @param UserPostFollowRequest $request
     * @param FollowUseCase $useCase
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function postFollow(UserPostFollowRequest $request, FollowUseCase $useCase)
    {
        // TODO: forbidをalreadyとsameで分けたい
        if (!$useCase($request->target_user_id)) return err_response(['message' => config('errors.follow.forbid')], 400);
        return response('{}', 200);
    }

    /**
     * アンフォロー
     *
     * @param UserUnfollowRequest $request
     * @param UnfollowUseCase $useCase
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function unfollow(UserUnfollowRequest $request, UnfollowUseCase $useCase)
    {
        // TODO: forbidをalreadyとsameで分けたい
        if (!$useCase($request->target_user_id)) return err_response(['message' => config('errors.follow.forbid')], 400);
        return response('{}', 200);
    }

    /**
     * フォロー一覧
     *
     * @param UserGetFollowRequest $request
     * @param FetchFollowUseCase $useCase
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function getFollow(UserGetFollowRequest $request, FetchFollowUseCase $useCase)
    {
        $response = $useCase($request->user_id, $request->follow_id, $request->type);
        return response($response, 200);
    }

    /**
     * フォロワー一覧
     *
     * @param UserFollowerRequest $request
     * @param FetchFollowerUseCase $useCase
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function follower(UserFollowerRequest $request, FetchFollowerUseCase $useCase)
    {
        $response = $useCase($request->user_id, $request->follow_id, $request->type);
        return response($response, 200);
    }
}
