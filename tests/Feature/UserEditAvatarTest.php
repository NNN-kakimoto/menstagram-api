<?php

namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use Tests\Feature\DataProviders\UserEditAvatarDataProvider;
use Tests\TestCase;

/**
 * ユーザーのアバター編集
 *
 * Class UserEditAvatarTest
 * @package Tests\Feature
 */
class UserEditAvatarTest extends TestCase
{
    use UserEditAvatarDataProvider;

    /**
     * 初期化処理
     */
    public function setUp(): void
    {
        parent::setUp();
        parent::seeding([\CreateUsersSeeder::class]);
    }

    /**
     * 正常系
     *
     * @test
     */
    public function successCase()
    {
        $accessToken = 'sQCeW8BEu0OvPULE1phO79gcenQevsamL2TA9yDruTinCAG1yfbNZn9O2udONJgLHH6psVWihISvCCqW';

        $file = UploadedFile::fake()->image('test.jpg', 100, 100);

        $response = $this
                        ->withHeader('Authorization', "Bearer $accessToken")
                        ->put('/api/v1/user/edit/avatar', [
                            'avatar' => $file,
                        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([]);
    }

    /**
     * 異常系(アバター)
     *
     * @test
     * @dataProvider avatarProvider
     * @param $file
     */
    public function failAvatarCase($file)
    {
        $accessToken = 'sQCeW8BEu0OvPULE1phO79gcenQevsamL2TA9yDruTinCAG1yfbNZn9O2udONJgLHH6psVWihISvCCqW';

        $response = $this
                        ->withHeader('Authorization', "Bearer $accessToken")
                        ->put('/api/v1/user/edit/avatar', [
                            'avatar' => $file,
                        ]);

        $response
            ->assertStatus(400)
            ->assertJsonValidationErrors(['avatar']);
    }

    /**
     * 異常系(Bodyなし)
     *
     * @test
     */
    public function failNoBodyCase()
    {
        $accessToken = 'sQCeW8BEu0OvPULE1phO79gcenQevsamL2TA9yDruTinCAG1yfbNZn9O2udONJgLHH6psVWihISvCCqW';

        $response = $this
                        ->withHeader('Authorization', "Bearer $accessToken")
                        ->put('/api/v1/user/edit/avatar', []);

        $response
            ->assertStatus(400)
            ->assertJsonValidationErrors(['message']);
    }
}
