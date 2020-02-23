<?php

namespace Tests\Feature;

use App\Models\Slurp;
use Illuminate\Http\UploadedFile;
use Tests\Feature\DataProviders\SlurpTextDataProvider;
use Tests\TestCase;

/**
 * スラープ(テキスト)
 *
 * Class SlurpTextTest
 * @package Tests\Feature
 */
class SlurpTextTest extends TestCase
{
    use SlurpTextDataProvider;

    protected $slurps;

    /**
     * 初期化処理
     */
    public function setUp(): void
    {
        parent::setUp();
        parent::seeding([\CreateUsersSeeder::class, \CreateSlurpsSeeder::class]);
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

        $file = UploadedFile::fake()->image('test.jpg', 100, 100);

        $response = $this
                    ->withHeader('Authorization', "Bearer $accessToken")
                    ->post('/api/v1/slurp', [
                        'image1' => $file,
                    ]);

        $slurpId = json_decode($response->getContent())->slurp_id;

        // TODO: 現状、is_ramens[0]がtrueの場合のみ走るようになっている
        // TODO: 100%ラーメンと判定される画像を投げるようにしたい
        if ($slurpId === 0) return;

        $response = $this
                        ->withHeader('Authorization', "Bearer $accessToken")
                        ->post('/api/v1/slurp/text', [
                            'slurp_id' => $slurpId,
                            'text'     => 'test',
                        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([]);
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
                        ->post('/api/v1/slurp/text', [
                            'slurp_id' => $slurpId,
                            'text'     => 'test',
                        ]);

        $response
            ->assertStatus(400)
            ->assertJsonValidationErrors(['slurp_id']);
    }

    /**
     * 異常系(スラープの書き込み権限無し)
     *
     * @param $slurpId
     * @dataProvider forbidSlurpProvider
     * @test
     */
    public function failForbidSlurpCase($slurpId)
    {
        $accessToken = 'sQCeW8BEu0OvPULE1phO79gcenQevsamL2TA9yDruTinCAG1yfbNZn9O2udONJgLHH6psVWihISvCCqW';

        $response = $this
                        ->withHeader('Authorization', "Bearer $accessToken")
                        ->post('/api/v1/slurp/text', [
                            'slurp_id' => $slurpId,
                            'text'     => 'test',
                        ]);

        $response
            ->assertStatus(403)
            ->assertJsonValidationErrors(['message']);
    }

    /**
     * 異常系(テキスト)
     *
     * @test
     * @dataProvider textProvider
     * @param $text
     */
    public function failTextCase($text)
    {
        $accessToken = 'sQCeW8BEu0OvPULE1phO79gcenQevsamL2TA9yDruTinCAG1yfbNZn9O2udONJgLHH6psVWihISvCCqW';

        $file = UploadedFile::fake()->image('test.jpg', 100, 100);

        $response = $this
                        ->withHeader('Authorization', "Bearer $accessToken")
                        ->post('/api/v1/slurp', [
                            'image1' => $file,
                        ]);

        $slurpId = json_decode($response->getContent())->slurp_id;

        $response = $this
                        ->withHeader('Authorization', "Bearer $accessToken")
                        ->post('/api/v1/slurp/text', [
                            'slurp_id' => $slurpId,
                            'text'     => $text,
                        ]);

        $response
            ->assertStatus(400)
            ->assertJsonValidationErrors(['text']);
    }
}
