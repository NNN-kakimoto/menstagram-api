<?php

namespace Tests\Feature\DataProviders;

/**
 * スラープ
 *
 * Trait SlurpTextDataProvider
 * @package Tests\Feature\DataProviders
 */
trait SlurpTextDataProvider
{
    /**
     * 異常系(スラープID)
     *
     * @return array
     */
    public function slurpIdProvider()
    {
        return [
            'スラープIDが存在しないパターン' => [null],
            'スラープIDが数値ではないパターン' => ['test'],
            'スラープIDが有効ではないパターン' => [999],
        ];
    }

    /**
     * 異常系(スラープの書き込み権限無し)
     *
     * @return array
     */
    public function forbidSlurpProvider()
    {
        $slurp_id = 1;
        foreach ($this->slurps as $slurp) {
            if ($slurp->user_id !== 1) {
                $slurp_id = $slurp->id;
                break;
            }
        }

        return [
            'スラープの書き込み権限が無いパターン' => [$slurp_id],
        ];
    }

    /**
     * 異常系(テキスト)
     *
     * @return array
     */
    public function textProvider()
    {
        return [
            'テキストが存在しないパターン' => [null],
            'テキストが文字列ではないパターン' => [0],
            'テキストが空白のパターン' => [''],
            'テキストが256文字を超えるパターン' => ['testtesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttest'],
        ];
    }
}