<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2017 Chengdu ZhiYiChuangXiang Technology Co., Ltd.     |
 * +----------------------------------------------------------------------+
 * | This source file is subject to version 2.0 of the Apache license,    |
 * | that is bundled with this package in the file LICENSE, and is        |
 * | available through the world-wide-web at the following url:           |
 * | http://www.apache.org/licenses/LICENSE-2.0.html                      |
 * +----------------------------------------------------------------------+
 * | Author: Slim Kit Group <master@zhiyicx.com>                          |
 * | Homepage: www.thinksns.com                                           |
 * +----------------------------------------------------------------------+
 */

namespace Zhiyi\PlusGroup\Tests\Feature\API2;

use Zhiyi\Plus\Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Zhiyi\Plus\Models\Tag as TagModel;
use Zhiyi\Plus\Models\User as UserModel;
use Zhiyi\PlusGroup\Models\Category as CateModel;
use Zhiyi\Plus\Models\TagCategory as TagCateModel;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateGroupTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;

    protected $cate;

    protected $tag;

    public function setUp()
    {
        parent::setUp();
        config([
            'plus-group.group_create.need_verified' => false
        ]);
        $this->user = factory(UserModel::class)->create();
        $this->cate = factory(CateModel::class)->create();
        $this->tag = factory(TagModel::class)->create([
            'tag_category_id' => $this->cate->id,
        ]);
    }

    /**
     * 创建普通圈子.
     *
     * @return mxied
     */
    public function testCreatePublicGroup()
    {
        $response = $this
            ->actingAs($this->user, 'api')
            ->json('POST', "/api/v2/plus-group/categories/{$this->cate->id}/groups", [
                'name' => 'test',
                'avatar' => UploadedFile::fake()->image('test.jpg')->size(0.00),
                'summary' => 'test',
                'notice' => 'test',
                'tags' => [
                    ['id' => $this->tag->id]
                ],
                'mode' => 'public',

            ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure(['message', 'group']);
    }

    /**
     * 创建私密圈子.
     *
     * @return mxied
     */
    public function testCreatePrivateGroup()
    {
        $response = $this
            ->actingAs($this->user, 'api')
            ->json('POST', "/api/v2/plus-group/categories/{$this->cate->id}/groups", [
                'name' => 'test',
                'avatar' => UploadedFile::fake()->image('test.jpg')->size(0.00),
                'summary' => 'test',
                'notice' => 'test',
                'tags' => [
                    ['id' => $this->tag->id]
                ],
                'mode' => 'private',

            ]);
        $response
            ->assertStatus(200)
            ->assertJsonStructure(['message', 'group']);
    }

    /**
     * 创建收费圈子.
     *
     * @return mxied
     */
    public function testCreatePaidGroup()
    {
        $response = $this
            ->actingAs($this->user, 'api')
            ->json('POST', "/api/v2/plus-group/categories/{$this->cate->id}/groups", [
                'name' => 'test',
                'avatar' => UploadedFile::fake()->image('test.jpg')->size(0.00),
                'summary' => 'test',
                'notice' => 'test',
                'tags' => [
                    ['id' => $this->tag->id]
                ],
                'mode' => 'paid',
                'money' => 100,

            ]);
        $response
            ->assertStatus(200)
            ->assertJsonStructure(['message', 'group']);
    }
}
