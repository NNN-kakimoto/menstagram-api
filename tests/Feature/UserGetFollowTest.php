<?php

namespace Tests\Feature;

use Tests\Feature\DataProviders\UserGetFollowDataProvider;
use Tests\TestCase;

/**
 * フォロー一覧
 *
 * Class UserGetFollowTest
 * @package Tests\Feature
 */
class UserGetFollowTest extends TestCase
{
    use UserGetFollowDataProvider;

    /**
     * 初期化処理
     */
    public function setUp(): void
    {
        parent::setUp();
        parent::seeding([\CreateUsersSeeder::class, \CreateFollowsSeeder::class]);
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
                        ->get('/api/v1/user/follow');

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
     * 正常系(ユーザーIDの指定あり)
     *
     * @test
     */
    public function successUserIdCase()
    {
        $accessToken = 'sQCeW8BEu0OvPULE1phO79gcenQevsamL2TA9yDruTinCAG1yfbNZn9O2udONJgLHH6psVWihISvCCqW';

        $response = $this
                        ->withHeader('Authorization', "Bearer $accessToken")
                        ->json('GET', '/api/v1/user/follow', [
                            'user_id' => 'menstagram',
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
     * 正常系(フォローIDの指定あり)
     *
     * @test
     */
    public function successFollowIdCase()
    {
        $accessToken = 'sQCeW8BEu0OvPULE1phO79gcenQevsamL2TA9yDruTinCAG1yfbNZn9O2udONJgLHH6psVWihISvCCqW';

        $response = $this
                        ->withHeader('Authorization', "Bearer $accessToken")
                        ->json('GET', '/api/v1/user/follow', [
                            'follow_id' => 5,
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
     * 正常系(フォローID, typeの指定あり)
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
                        ->json('GET', '/api/v1/user/follow', [
                            'follow_id' => 5,
                            'type'      => $type,
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
     * 異常系(ユーザーID)
     *
     * @test
     * @dataProvider failUserIdProvider
     * @param $userId
     */
    public function failUserIdCase($userId)
    {
        $accessToken = 'sQCeW8BEu0OvPULE1phO79gcenQevsamL2TA9yDruTinCAG1yfbNZn9O2udONJgLHH6psVWihISvCCqW';

        $response = $this
                        ->withHeader('Authorization', "Bearer $accessToken")
                        ->json('GET', '/api/v1/user/follow', [
                            'user_id' => $userId,
                        ]);

        $response
            ->assertStatus(400)
            ->assertJsonValidationErrors(['user_id']);
    }

    /**
     * 異常系(フォローID)
     *
     * @test
     * @dataProvider failFollowIdProvider
     * @param $followId
     */
    public function failFollowIdCase($followId)
    {
        $accessToken = 'sQCeW8BEu0OvPULE1phO79gcenQevsamL2TA9yDruTinCAG1yfbNZn9O2udONJgLHH6psVWihISvCCqW';

        $response = $this
                        ->withHeader('Authorization', "Bearer $accessToken")
                        ->json('GET', '/api/v1/user/follow', [
                            'follow_id' => $followId,
                        ]);

        $response
            ->assertStatus(400)
            ->assertJsonValidationErrors(['follow_id']);
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
                        ->json('GET', '/api/v1/user/follow', [
                            'type' => $type,
                        ]);

        $response
            ->assertStatus(400)
            ->assertJsonValidationErrors(['type']);
    }
}
