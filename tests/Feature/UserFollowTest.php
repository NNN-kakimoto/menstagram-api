<?php

namespace Tests\Feature;

use App\Models\Follow;
use App\Models\User;
use Tests\Feature\DataProviders\UserFollowDataProvider;
use Tests\TestCase;

/**
 * フォロー
 *
 * Class UserFollowTest
 * @package Tests\Feature
 */
class UserFollowTest extends TestCase
{
    use UserFollowDataProvider;

    protected $users;
    protected $follows;

    /**
     * 初期化処理
     */
    public function setUp(): void
    {
        parent::setUp();
        parent::seeding([\CreateUsersSeeder::class, \CreateFollowsSeeder::class]);
        $this->users = User::all();
        $this->follows = Follow::all();
    }

    /**
     * 正常系
     *
     * @test
     */
    public function successCase()
    {
        foreach ($this->follows as $follow) {
            if ($follow->user_id === $this->users[0]->id && $follow->target_user_id === $this->users[1]->id) {
                $this->follows
                        ->where('user_id', $follow->user_id)
                        ->where('target_user_id', $follow->target_user_id)->each->delete();
            }
        }

        $accessToken = 'sQCeW8BEu0OvPULE1phO79gcenQevsamL2TA9yDruTinCAG1yfbNZn9O2udONJgLHH6psVWihISvCCqW';

        $response = $this
                        ->withHeader('Authorization', "Bearer $accessToken")
                        ->post('/api/v1/user/follow', [
                            'target_user_id' => $this->users[1]->user_id,
                        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([]);

        $this->assertDatabaseHas('follows', [
            'user_id'        => $this->users[0]->id,
            'target_user_id' => $this->users[1]->id,
        ]);
    }

    /**
     * 異常系(フォロー対象のユーザーID)
     *
     * @test
     * @dataProvider targetUserIdProvider
     * @param $targetUserId
     */
    public function failTargetUserIdCase($targetUserId)
    {
        $accessToken = 'sQCeW8BEu0OvPULE1phO79gcenQevsamL2TA9yDruTinCAG1yfbNZn9O2udONJgLHH6psVWihISvCCqW';

        $response = $this
                        ->withHeader('Authorization', "Bearer $accessToken")
                        ->post('/api/v1/user/follow', [
                            'target_user_id' => $targetUserId,
                        ]);

        $response
            ->assertStatus(400)
            ->assertJsonValidationErrors(['target_user_id']);

        $this->assertDatabaseMissing('follows', [
            'user_id'        => $this->users[0]->id,
            'target_user_id' => $targetUserId,
        ]);
    }

    /**
     * 異常系(すでにフォローしているパターン)
     *
     * @test
     */
    public function failAlreadyCase()
    {
        $accessToken = 'sQCeW8BEu0OvPULE1phO79gcenQevsamL2TA9yDruTinCAG1yfbNZn9O2udONJgLHH6psVWihISvCCqW';
        $targetUserId = $this->users[1]->user_id;

        $this
            ->withHeader('Authorization', "Bearer $accessToken")
            ->post('/api/v1/user/follow', [
                'target_user_id' => $targetUserId,
            ]);

        $response = $this
            ->withHeader('Authorization', "Bearer $accessToken")
            ->post('/api/v1/user/follow', [
                'target_user_id' => $targetUserId,
            ]);

        $response
            ->assertStatus(400)
            ->assertJsonValidationErrors(['message']);

        $this->assertDatabaseMissing('follows', [
            'user_id'        => $this->users[0]->id,
            'target_user_id' => $targetUserId,
        ]);
    }

    /**
     * 異常系(フォロー対象がログインユーザーと同一のパターン)
     *
     * @test
     */
    public function failSameCase()
    {
        $accessToken = 'sQCeW8BEu0OvPULE1phO79gcenQevsamL2TA9yDruTinCAG1yfbNZn9O2udONJgLHH6psVWihISvCCqW';
        $targetUserId = $this->users[0]->user_id;

        $response = $this
            ->withHeader('Authorization', "Bearer $accessToken")
            ->post('/api/v1/user/follow', [
                'target_user_id' => $targetUserId,
            ]);

        $response
            ->assertStatus(400)
            ->assertJsonValidationErrors(['message']);

        $this->assertDatabaseMissing('follows', [
            'user_id'        => $this->users[0]->id,
            'target_user_id' => $targetUserId,
        ]);
    }
}
