<?php

namespace Tests\Feature;

use Tests\Feature\DataProviders\UserProfileDataProvider;
use Tests\TestCase;

/**
 * ユーザーのプロフィール
 *
 * Class UserProfileTest
 * @package Tests\Feature
 */
class UserProfileTest extends TestCase
{
    use UserProfileDataProvider;

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
                        ->json('GET', '/api/v1/user/profile', [
                            'user_id' => 'menstagram',
                        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'user_id',
                'user_name',
                'email',
                'avatar',
                'biography',
                'slurp_count',
                'follow_count',
                'follower_count',
                'is_follow',
            ]);
    }

    /**
     * 異常系(ユーザーID)
     *
     * @test
     * @dataProvider userIdProvider
     * @param $userId
     */
    public function failCase($userId)
    {
        $accessToken = 'sQCeW8BEu0OvPULE1phO79gcenQevsamL2TA9yDruTinCAG1yfbNZn9O2udONJgLHH6psVWihISvCCqW';

        $response = $this
                        ->withHeader('Authorization', "Bearer $accessToken")
                        ->json('GET', '/api/v1/user/profile', [
                            'user_id' => $userId,
                        ]);

        $response
            ->assertStatus(400)
            ->assertJsonValidationErrors(['user_id']);
    }
}
