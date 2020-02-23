<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Arr;
use Tests\Feature\DataProviders\AuthRegisterDataProvider;
use Tests\TestCase;

/**
 * ユーザーの登録
 *
 * Class AuthRegisterTest
 * @package Tests\Feature
 */
class AuthRegisterTest extends TestCase
{
    use AuthRegisterDataProvider;

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
            'user_id'   => 'Menstagram_9999',
            'user_name' => 'Menstagram_9999',
            'email'     => 'menstagram_9999@menstagram.com',
            'password'  => 'Menstagram_9999',
        ];

        $response = $this->post('/api/v1/auth/register', $user);

        $response
            ->assertStatus(200)
            ->assertJsonStructure(['access_token']);

        $this->assertDatabaseHas('users', Arr::except($user, 'password'));
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
        $user = [
            'user_id'   => $userId,
            'user_name' => 'Menstagram9999',
            'email'     => 'menstagram9999@menstagram.com',
            'password'  => 'Menstagram9999',
        ];

        $response = $this->post('/api/v1/auth/register', $user);
        $response
            ->assertStatus(400)
            ->assertJsonValidationErrors(['user_id']);

        $this->assertDatabaseMissing('users', Arr::except($user, 'password'));
    }

    /**
     * 異常系(ユーザーネーム)
     *
     * @param $userName
     */
    public function failScreenNameCase($userName)
    {
        $user = [
            'user_id'   => 'Menstagram_9999',
            'user_name' => $userName,
            'email'     => 'menstagram_9999@menstagram.com',
            'password'  => 'Menstagram_9999',
        ];

        $response = $this->post('/api/v1/auth/register', $user);
        $response
            ->assertStatus(400)
            ->assertJsonValidationErrors(['user_name']);

        $this->assertDatabaseMissing('users', Arr::except($user, 'password'));
    }

    /**
     * 異常系(メールアドレス)
     *
     * @test
     * @dataProvider emailProvider
     * @param $email
     */
    public function failEmailCase($email)
    {
        $user = [
            'user_id'   => 'Menstagram_9999',
            'user_name' => 'Menstagram9999',
            'email'     => $email,
            'password'  => 'Menstagram_9999',
        ];

        $response = $this->post('/api/v1/auth/register', $user);
        $response
            ->assertStatus(400)
            ->assertJsonValidationErrors(['email']);

        $this->assertDatabaseMissing('users', Arr::except($user, 'password'));
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
        $user = [
            'user_id'   => 'Menstagram_9999',
            'user_name' => 'Menstagram9999',
            'email'     => 'menstagram_9999@menstagram.com',
            'password'  => $password,
        ];

        $response = $this->post('/api/v1/auth/register', $user);
        $response
            ->assertStatus(400)
            ->assertJsonValidationErrors(['password']);

        $this->assertDatabaseMissing('users', Arr::except($user, 'password'));
    }
}
