<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2016-Present ZhiYiChuangXiang Technology Co., Ltd.     |
 * +----------------------------------------------------------------------+
 * | This source file is subject to enterprise private license, that is   |
 * | bundled with this package in the file LICENSE, and is available      |
 * | through the world-wide-web at the following url:                     |
 * | https://github.com/slimkit/plus/blob/master/LICENSE                  |
 * +----------------------------------------------------------------------+
 * | Author: Slim Kit Group <master@zhiyicx.com>                          |
 * | Homepage: www.thinksns.com                                           |
 * +----------------------------------------------------------------------+
 */

namespace Zhiyi\Plus\Tests\Feature\API2;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Zhiyi\Plus\Models\File as FileModel;
use Zhiyi\Plus\Models\User as UserModel;
use Zhiyi\Plus\Tests\TestCase;

class FileUploadedTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * The test user.
     *
     * @var Zhiyi\Plus\Models\User
     */
    protected $user;

    /**
     * The test set up fearure.
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function setUp()
    {
        parent::setUp();

        $this->user = factory(UserModel::class)->create();
    }

    /**
     * Test not uploaded file hash check.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function testNotHash()
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->image('test.jpg');
        $hash = md5_file((string) $file);
        $response = $this
            ->actingAs($this->user, 'api')
            ->json('GET', '/api/v2/files/uploaded/'.$hash);

        $response->assertStatus(404);
    }

    /**
     * Test Uploaded file hash.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function testUsedHash()
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->image('test.jpg');
        $hash = md5_file((string) $file);

        factory(FileModel::class)->create([
            'hash' => $hash,
            'origin_filename' => 'test.jpg',
            'filename' => 'test.jpg',
            'mime' => 'image/jpeg',
            'width' => '0.00',
            'height' => '0.00',
        ]);
        $response = $this
            ->actingAs($this->user, 'api')
            ->json('GET', '/api/v2/files/uploaded/'.$hash);

        $response
            ->assertStatus(200)
            ->assertJsonStructure(['message', 'id']);
    }
}
