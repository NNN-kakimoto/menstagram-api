<?php

namespace Tests\Feature;

use Tests\Feature\DataProviders\TimelinePrivateDataProvider;
use Tests\TestCase;

/**
 * プライベートタイムライン
 *
 * Class TimelinePrivateTest
 * @package Tests\Feature
 */
class TimelinePrivateTest extends TestCase
{
    use TimelinePrivateDataProvider;

    /**
     * 初期化処理
     */
    public function setUp(): void
    {
        parent::setUp();
        parent::seeding([\CreateUsersSeeder::class, \CreateSlurpsSeeder::class]);
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
                        ->get('/api/v1/timeline/private');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'text',
                    'images' => [
                        '*' => [],
                    ],
                    'user' => [
                        'user_id',
                        'user_name',
                        'avatar',
                    ],
                    'yum_count',
                    'is_yum',
                    'created_at',
                    'updated_at',
                ],
            ]);
    }

    /**
     * 正常系(スラープIDあり)
     *
     * @test
     */
    public function successSlurpIdCase()
    {
        $accessToken = 'sQCeW8BEu0OvPULE1phO79gcenQevsamL2TA9yDruTinCAG1yfbNZn9O2udONJgLHH6psVWihISvCCqW';

        $response = $this
                        ->withHeader('Authorization', "Bearer $accessToken")
                        ->json('GET', '/api/v1/timeline/private', [
                            'slurp_id' => 50,
                        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'text',
                    'images' => [
                        '*' => [],
                    ],
                    'user' => [
                        'user_id',
                        'user_name',
                        'avatar',
                    ],
                    'yum_count',
                    'is_yum',
                    'created_at',
                    'updated_at',
                ],
            ]);
    }

    /**
     * 正常系(スラープID, typeあり)
     *
     * @test
     * @dataProvider successTypeProvider
     * @param $type
     */
    public function successTypeCase($type)
    {
        $accessToken = 'sQCeW8BEu0OvPULE1phO79gcenQevsamL2TA9yDruTinCAG1yfbNZn9O2udONJgLHH6psVWihISvCCqW';

        $response = $this
                        ->withHeader('Authorization', "Bearer $accessToken")
                        ->json('GET', '/api/v1/timeline/private', [
                            'slurp_id' => 50,
                            'type'     => $type,
                        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'text',
                    'images' => [
                        '*' => [],
                    ],
                    'user' => [
                        'user_id',
                        'user_name',
                        'avatar',
                    ],
                    'yum_count',
                    'is_yum',
                    'created_at',
                    'updated_at',
                ],
            ]);
    }

    /**
     * 異常系(スラープID)
     *
     * @test
     * @dataProvider failSlurpIdProvider
     * @param $slurpId
     */
    public function failSlurpIdCase($slurpId)
    {
        $accessToken = 'sQCeW8BEu0OvPULE1phO79gcenQevsamL2TA9yDruTinCAG1yfbNZn9O2udONJgLHH6psVWihISvCCqW';

        $response = $this
                        ->withHeader('Authorization', "Bearer $accessToken")
                        ->json('GET', '/api/v1/timeline/private', [
                            'slurp_id' => $slurpId,
                        ]);

        $response
            ->assertStatus(400)
            ->assertJsonValidationErrors(['slurp_id']);
    }

    /**
     * 異常系(type)
     *
     * @test
     * @dataProvider failTypeProvider
     * @param $type
     */
    public function failTypeCase($type)
    {
        $accessToken = 'sQCeW8BEu0OvPULE1phO79gcenQevsamL2TA9yDruTinCAG1yfbNZn9O2udONJgLHH6psVWihISvCCqW';

        $response = $this
                        ->withHeader('Authorization', "Bearer $accessToken")
                        ->json('GET', '/api/v1/timeline/private', [
                            'slurp_id' => 50,
                            'type'     => $type,
                        ]);

        $response
            ->assertStatus(400)
            ->assertJsonValidationErrors(['type']);
    }
}
