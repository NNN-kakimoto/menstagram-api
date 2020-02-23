<?php

namespace Tests\Feature;

use App\Models\Yum;
use Tests\Feature\DataProviders\SlurpUnyumDataProvider;
use Tests\TestCase;

/**
 * ヤムを外す
 *
 * Class SlurpUnyumTest
 * @package Tests\Feature
 */
class SlurpUnyumTest extends TestCase
{
    use SlurpUnyumDataProvider;

    protected $yums;

    /**
     * 初期化処理
     */
    public function setUp(): void
    {
        parent::setUp();
        parent::seeding([\CreateSlurpsSeeder::class, \CreateYumsSeeder::class]);
        $this->yums = Yum::all();
    }

    /**
     * 正常系
     *
     * @test
     */
    public function successCase()
    {
        $accessToken = 'sQCeW8BEu0OvPULE1phO79gcenQevsamL2TA9yDruTinCAG1yfbNZn9O2udONJgLHH6psVWihISvCCqW';
        $userId = 1;
        $slurpId = 1;

        $this->yums->where('user_id', $userId)->where('slurp_id', $slurpId)->each->delete();

        $this
            ->withHeader('Authorization', "Bearer $accessToken")
            ->post('/api/v1/slurp/yum', [
                'slurp_id' => $slurpId,
            ]);

        $response = $this
                        ->withHeader('Authorization', "Bearer $accessToken")
                        ->post('/api/v1/slurp/unyum', [
                            'slurp_id' => $slurpId,
                        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([]);

        $this->assertDatabaseMissing('yums', [
            'user_id'  => $userId,
            'slurp_id' => $slurpId,
        ]);
    }

    /**
     * 異常系(スラープID)
     *
     * @test
     * @dataProvider slurpIdProvider
     * @param $slurpId
     */
    public function failSlurpIdCase($slurpId)
    {
        $accessToken = 'sQCeW8BEu0OvPULE1phO79gcenQevsamL2TA9yDruTinCAG1yfbNZn9O2udONJgLHH6psVWihISvCCqW';

        $response = $this
                        ->withHeader('Authorization', "Bearer $accessToken")
                        ->post('/api/v1/slurp/unyum', [
                            'slurp_id' => $slurpId,
                        ]);

        $response
            ->assertStatus(400)
            ->assertJsonValidationErrors(['slurp_id']);
    }

    /**
     * 異常系(ヤムされていないスラープのパターン)
     *
     * @test
     */
    public function failYetCase()
    {
        $accessToken = 'sQCeW8BEu0OvPULE1phO79gcenQevsamL2TA9yDruTinCAG1yfbNZn9O2udONJgLHH6psVWihISvCCqW';
        $slurpId = 1;

        $this->yums->where('user_id', 1)->where('slurp_id', $slurpId)->each->delete();

        $response = $this
                        ->withHeader('Authorization', "Bearer $accessToken")
                        ->post('/api/v1/slurp/unyum', [
                            'slurp_id' => $slurpId,
                        ]);

        $response
            ->assertStatus(400)
            ->assertJsonValidationErrors(['message']);
    }
}
