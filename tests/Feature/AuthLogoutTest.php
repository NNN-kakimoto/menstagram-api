<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Arr;
use Tests\Feature\DataProviders\AuthLogoutDataProvider;
use Tests\TestCase;

/**
 * ユーザーのログアウト
 *
 * Class AuthLogoutTest
 * @package Tests\Feature
 */
class AuthLogoutTest extends TestCase
{
    use AuthLogoutDataProvider;

    protected $users;

    /**
     * 初期化処理
     */
    public function setUp(): void
    {
        parent::setUp();
        parent::seeding([\CreateUsersSeeder::class]);
        $this->users = User::all();
    }

    /**
     * 正常系
     *
     * @test
     */
    public function successCase()
    {
        $user = Arr::first($this->users, function ($value, $key) {
            return $value->access_token !== null;
        });
        $accessToken = 'sQCeW8BEu0OvPULE1phO79gcenQevsamL2TA9yDruTinCAG1yfbNZn9O2udONJgLHH6psVWihISvCCqW';

        $response = $this
                        ->withHeader('Authorization', "Bearer $accessToken")
                        ->post('/api/v1/auth/logout');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([]);

        $this->assertDatabaseHas('users', [
            'user_id'      => $user['user_id'],
            'access_token' => null,
        ]);
    }

    /**
     * 異常系
     *
     * @test
     * @dataProvider accessTokenProvider
     * @param $accessToken
     */
    public function failCase($accessToken)
    {
        $response = is_null($accessToken) ?
                        $this
                            ->post('/api/v1/auth/logout')
                    :
                        $this
                            ->withHeader('Authorization', $accessToken)
                            ->post('/api/v1/auth/logout');

        $response
            ->assertStatus(401)
            ->assertJsonValidationErrors(['message']);
    }
}
