<?php

namespace Tests\Feature;

use Tests\Feature\DataProviders\SlurpYumsDataProvider;
use Tests\TestCase;

/**
 * スラープにヤムしたユーザー一覧
 *
 * Class SlurpYumsTest
 * @package Tests\Feature
 */
class SlurpYumsTest extends TestCase
{
    use SlurpYumsDataProvider;

    /**
     * 正常系
     *
     * @test
     */
    public function successCase()
    {
        $accessToken = 'sQCeW8BEu0OvPULE1phO79gcenQevsamL2TA9yDruTinCAG1yfbNZn9O2udONJgLHH6psVWihISvCCqW';

        $response = $this
            ->withHeader('Authorization', "Bearer $accessToken")
            ->json('GET', '/api/v1/slurp/yums', [
                'slurp_id' => 1,
            ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'user_id',
                    'user_name',
                    'avatar',
                    'is_follow',
                    'is_me',
                ]
            ]);
    }

    /**
     * 異常系
     *
     * @test
     * @dataProvider slurpIdProvider
     * @param $slurpId
     */
    public function failCase($slurpId)
    {
        $accessToken = 'sQCeW8BEu0OvPULE1phO79gcenQevsamL2TA9yDruTinCAG1yfbNZn9O2udONJgLHH6psVWihISvCCqW';

        $response = $this
            ->withHeader('Authorization', "Bearer $accessToken")
            ->json('GET', '/api/v1/slurp/yums', [
                'slurp_id' => $slurpId,
            ]);

        $response
            ->assertStatus(400)
            ->assertJsonValidationErrors(['slurp_id']);
    }
}
