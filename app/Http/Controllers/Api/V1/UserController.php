<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserEditRequest;
use App\Http\Requests\UserFollowRequest;
use App\Http\Requests\UserLikesRequest;
use App\Http\Requests\UserProfileRequest;
use App\Http\Requests\UserUnfollowRequest;
use App\UseCases\FetchUserLikesUseCase;
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
     * いいねした投稿一覧
     *
     * @param UserLikesRequest $request
     * @param FetchUserLikesUseCase $useCase
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function likes(UserLikesRequest $request, FetchUserLikesUseCase $useCase)
    {
        $response = $useCase($request->post_id, $request->type);
        return response($response, 200);
    }

    /**
     * フォロー
     *
     * @param UserFollowRequest $request
     * @param FollowUseCase $useCase
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function follow(UserFollowRequest $request, FollowUseCase $useCase)
    {
        if (!$useCase($request->user_id)) return response('{}', 400);
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
        if (!$useCase($request->user_id)) return response('{}', 400);
        return response('{}', 200);
    }
}
