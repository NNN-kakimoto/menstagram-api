<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\SlurpDetailRequest;
use App\Http\Requests\SlurpYumRequest;
use App\Http\Requests\SlurpYumsRequest;
use App\Http\Requests\SlurpRequest;
use App\Http\Requests\SlurpTextRequest;
use App\Http\Requests\SlurpUnyumRequest;
use App\UseCases\FetchSlurpDetailUseCase;
use App\UseCases\FetchSlurpYumsUseCase;
use App\UseCases\JudgeRamenUseCase;
use App\UseCases\YumUseCase;
use App\UseCases\SlurpTextUseCase;
use App\UseCases\SlurpUseCase;
use App\UseCases\PreprocessImagesUseCase;
use App\UseCases\StoreImagesUseCase;
use App\UseCases\UnyumUseCase;

/**
 * スラープ系API
 *
 * Class SlurpController
 * @package App\Http\Controllers\Api\V1
 */
class SlurpController extends Controller
{
    /**
     * スラープ(画像)
     *
     * @param SlurpRequest $request
     * @param PreprocessImagesUseCase $preprocessImagesUseCase
     * @param JudgeRamenUseCase $judgeRamenUseCase
     * @param StoreImagesUseCase $storeImagesUseCase
     * @param SlurpUseCase $slurpUseCase
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function slurp(SlurpRequest $request,
                          PreprocessImagesUseCase $preprocessImagesUseCase,
                          JudgeRamenUseCase $judgeRamenUseCase,
                          StoreImagesUseCase $storeImagesUseCase,
                          SlurpUseCase $slurpUseCase)
    {
        $images = $preprocessImagesUseCase($request);
        $isRamens = $judgeRamenUseCase($images);
        $filePaths = $storeImagesUseCase($images, $isRamens);
        $response = $slurpUseCase($filePaths, $isRamens);
        return response($response, 200);
    }

    /**
     * スラープ(テキスト)
     *
     * @param SlurpTextRequest $request
     * @param SlurpTextUseCase $useCase
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function text(SlurpTextRequest $request, SlurpTextUseCase $useCase)
    {
        if (!$useCase($request)) return err_response(['message' => config('errors.slurp.forbid')], 403);
        return response('{}', 200);
    }

    /**
     * ヤム
     *
     * @param SlurpYumRequest $request
     * @param YumUseCase $useCase
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function yum(SlurpYumRequest $request, YumUseCase $useCase)
    {
        if (!$useCase($request->slurp_id)) return err_response(['message' => config('errors.slurp.already')], 400);
        return response('{}', 200);
    }

    /**
     * ヤムを外す
     *
     * @param SlurpUnyumRequest $request
     * @param UnyumUseCase $useCase
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function unyum(SlurpUnyumRequest $request, UnyumUseCase $useCase)
    {
        if(!$useCase($request->slurp_id)) return err_response(['message' => config('errors.slurp.yet')], 400);
        return response('{}', 200);
    }

    /**
     * スラープ詳細
     *
     * @param SlurpDetailRequest $request
     * @param FetchSlurpDetailUseCase $useCase
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function detail(SlurpDetailRequest $request, FetchSlurpDetailUseCase $useCase)
    {
        $response = $useCase($request->slurp_id);
        return response($response, 200);
    }

    /**
     * ヤムしたユーザー一覧
     *
     * @param SlurpYumsRequest $request
     * @param FetchSlurpYumsUseCase $useCase
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function yums(SlurpYumsRequest $request, FetchSlurpYumsUseCase $useCase)
    {
        $response = $useCase($request->slurp_id);
        return response($response, 200);
    }
}
