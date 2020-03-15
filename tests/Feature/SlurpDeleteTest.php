<?php

namespace Tests\Feature;

use App\Models\Slurp;
use App\Models\Yum;
use Tests\Feature\DataProviders\SlurpDeleteDataProvider;
use Tests\TestCase;

/**
 * スラープの削除
 *
 * Class SlurpDeleteTest
 * @package Tests\Feature
 */
class SlurpDeleteTest extends TestCase
{
    use SlurpDeleteDataProvider;

    protected $yums;
    protected $slurps;

    /**
     * 初期化処理
     */
    public function setUp(): void
    {
        parent::setUp();
        parent::seeding([\CreateSlurpsSeeder::class, \CreateYumsSeeder::class]);
        $this->yums = Yum::all();
        $this->slurps = Slurp::all();
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
        $slurpId = $this->slurps->where('user_id', $userId)->first()->id;

        $this->yums->where('user_id', $userId)->where('slurp_id', $slurpId)->each->delete();

        $response = $this
                        ->withHeader('Authorization', "Bearer $accessToken")
                        ->delete('/api/v1/slurp/delete', [
                            'slurp_id' => $slurpId,
                        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([]);

        $this->assertDatabaseMissing('slurps', [
            'id' => $slurpId,
        ]);
        $this->assertDatabaseMissing('yums', [
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
                        ->delete('/api/v1/slurp/delete', [
                            'slurp_id' => $slurpId,
                        ]);

        $response
            ->assertStatus(400)
            ->assertJsonValidationErrors(['slurp_id']);
    }

    /**
     * 異常系(他人のスラープを消そうとしたパターン)
     *
     * @test
     */
    public function failDeleteCase()
    {
        $accessToken = 'sQCeW8BEu0OvPULE1phO79gcenQevsamL2TA9yDruTinCAG1yfbNZn9O2udONJgLHH6psVWihISvCCqW';

        $this->yums->where('user_id', 1)->where('slurp_id', 1)->each->delete();
        $slurpId =  $this->slurps->where('user_id', '!=', 1)->first()->id;

        $response = $this
                        ->withHeader('Authorization', "Bearer $accessToken")
                        ->delete('/api/v1/slurp/delete', [
                            'slurp_id' => $slurpId,
                        ]);

        $response
            ->assertStatus(400)
            ->assertJsonValidationErrors(['message']);
        
        $this->assertDatabaseHas('slurps', [
            'id' => $slurpId,
        ]);
    }
}
