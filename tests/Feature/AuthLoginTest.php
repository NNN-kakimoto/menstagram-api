<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\Feature\DataProviders\AuthLoginDataProvider;
use Tests\TestCase;

/**
 * ユーザーのログイン
 *
 * Class AuthLoginTest
 * @package Tests\Feature
 */
class AuthLoginTest extends TestCase
{
    use AuthLoginDataProvider;

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
        $user = [
            'user_id'  => $this->users[0]->user_id,
            'password' => 'menstagram',
        ];

        $response = $this->post('/api/v1/auth/login', $user);

        $response
            ->assertStatus(200)
            ->assertJsonStructure(['access_token']);
    }

    /**
     * 異常系(ユーザーID)
     *
     * @test
     * @dataProvider userIdProvider
     * @param $userId
     */
    public function failUserIdCase($userId)
    {
        $response = $this->post('/api/v1/auth/login', [
            'user_id'  => $userId,
            'password' => 'menstagram',
        ]);

        $response
            ->assertStatus(400)
            ->assertJsonValidationErrors(['user_id']);
    }

    /**
     * 異常系(パスワード)
     *
     * @test
     * @dataProvider passwordProvider
     * @param $password
     */
    public function failPasswordCase($password)
    {
        $response = $this->post('/api/v1/auth/login', [
            'user_id'  => $this->users[0]->user_id,
            'password' => $password,
        ]);

        $response
            ->assertStatus(400)
            ->assertJsonValidationErrors(['password']);
    }

    /**
     * 異常系(ユーザーIDとパスワード)
     *
     * @test
     * @dataProvider userIdAndPasswordProvider
     * @param $userId
     * @param $password
     */
    public function failUserIdAndPasswordCase($userId, $password)
    {
        $response = $this->post('/api/v1/auth/login', [
            'user_id'  => $userId,
            'password' => $password,
        ]);

        $response
            ->assertStatus(400)
            ->assertJsonValidationErrors(['message']);
    }
}
