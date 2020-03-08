<?php

namespace Tests\Feature;

use App\Models\Slurp;
use Tests\Feature\DataProviders\SlurpDetailDataProvider;
use Tests\TestCase;

/**
 * スラープ詳細
 *
 * Class SlurpDetailTest
 * @package Tests\Feature
 */
class SlurpDetailTest extends TestCase
{
    use SlurpDetailDataProvider;

    protected $slurps;

    /**
     * 初期化処理
     */
    public function setUp(): void
    {
        parent::setUp();
        parent::seeding([\CreateSlurpsSeeder::class]);
        $this->slurps =  Slurp::all();
    }

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
                        ->json('GET', '/api/v1/slurp/detail', [
                            'slurp_id' => 1,
                        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'text',
                'images' => [],
                'yum_count',
                'is_yum',
                'created_at',
                'updated_at',
                'user' => [
                    'id',
                    'user_id',
                    'user_name',
                    'avatar',
                ],
                'yums' => [
                    '*' => [
                        'user_id',
                        'avatar',
                    ]
                ],
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
                        ->json('GET', '/api/v1/slurp/detail', [
                            'slurp_id' => $slurpId,
                        ]);

        $response
            ->assertStatus(400)
            ->assertJsonValidationErrors(['slurp_id']);
    }
}
