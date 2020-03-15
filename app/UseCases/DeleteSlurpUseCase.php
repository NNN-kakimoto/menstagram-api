<?php

namespace App\UseCases;

use App\Models\Yum;
use App\Models\Slurp;
use Illuminate\Support\Facades\DB;
/**
 * スラープを削除する
 *
 * Class DeleteSlurpUseCase
 * @package App\UseCases
 */
class DeleteSlurpUseCase
{
    public function __invoke($slurpId)
    {
        $userId = user()->id;
        $slurpUserId = Slurp::find($slurpId)->user_id;

        if($userId !== $slurpUserId) return false;

        DB::transaction(function () use ($userId, $slurpId) {
        Yum::where('slurp_id', $slurpId)->delete();
        Slurp::destroy($slurpId);
        }, 5);

        return true;
        

        
    }
}