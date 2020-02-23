<?php

namespace Tests\Feature;

use App\Models\Slurp;
use Illuminate\Http\UploadedFile;
use Tests\Feature\DataProviders\SlurpDataProvider;
use Tests\TestCase;

/**
 * スラープ(画像)
 *
 * Class SlurpTest
 * @package Tests\Feature
 */
class SlurpTest extends TestCase
{
    use SlurpDataProvider;

    protected $slurps;

    /**
     * 初期化処理
     */
    public function setUp(): void
    {
        parent::setUp();
        parent::seeding([\CreateSlurpsSeeder::class]);
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

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'slurp_id',
                'is_ramens' => [],
            ]);

        // TODO: 現状、is_ramens[0]がtrueの場合のみ走るようになっている
        // TODO: 100%ラーメンと判定される画像を投げるようにしたい
        if (json_decode($response->getContent())->is_ramens[0]) {
            $this->assertDatabaseHas('slurps', [
                'id' => json_decode($response->getContent())->slurp_id,
            ]);
        }

    }

    /**
     * 異常系(画像)
     *
     * @test
     * @dataProvider imagesProvider
     * @param $file
     */
    public function failImagesCase($file)
    {
        $accessToken = 'sQCeW8BEu0OvPULE1phO79gcenQevsamL2TA9yDruTinCAG1yfbNZn9O2udONJgLHH6psVWihISvCCqW';

        $response = $this
            ->withHeader('Authorization', "Bearer $accessToken")
            ->post('/api/v1/slurp', [
                'image1' => $file,
            ]);

        $response
            ->assertStatus(400)
            ->assertJsonValidationErrors(['image1']);
    }
}
