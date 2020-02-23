<?php

namespace App\UseCases;

use App\Models\Follow;

/**
 * フォロイー一覧の取得
 *
 * Class FetchFolloweeUseCase
 * @package App\UseCases
 */
class FetchFolloweeUseCase
{
    /**
     * @param null $userId
     * @param null $followId
     * @param null $type
     * @return Follow[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    public function __invoke($userId = null, $followId = null, $type = null)
    {
        $follow = $this->fetchFollow($userId, $followId, $type);

        $followByLoginUser = $this->fetchFollow()->map(function ($v, $k) {
            return $v->follow->id;
        });

        $follow = $follow->map(function ($v, $k) use ($followByLoginUser) {
            return collect($v->follow)
                        ->only(['user_id', 'user_name', 'avatar'])
                        ->put('is_follow', collect($followByLoginUser)->contains($v->follow->id) ? true : false)
                        ->put('is_me', user()->id === $v->follow->id ? true : false);
        });

        return $follow;
    }

    /**
     * フォロー一覧の取得
     *
     * @param null $userId
     * @param null $followId
     * @param null $type
     * @return \Illuminate\Support\Collection
     */
    private function fetchFollow($userId = null, $followId = null, $type = null)
    {
        $userId = $userId ? user($userId)->id : user()->id;

        $query = Follow::with(['follow'])->where('user_id', $userId);

        if (is_null($followId) && is_null($type))                            $query->latest('id');
        else if (!is_null($followId) && (is_null($type) || $type === 'new')) $query->where('id', '>=', $followId);
        else if (!is_null($followId) && $type === 'old')                     $query->where('id', '<=', $followId)->orderBy('id', 'desc');

        $follow = collect($query->limit(100)->get());
        if ($type != 'new') $follow = $follow->reverse()->values();
        return $follow;
    }
}