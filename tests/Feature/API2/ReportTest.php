<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2018 Chengdu ZhiYiChuangXiang Technology Co., Ltd.     |
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

namespace Zhiyi\Plus\Tests\Feature\API2;

use Zhiyi\Plus\Tests\TestCase;
use Zhiyi\Plus\Models\User as UserModel;
use Zhiyi\Plus\Models\Comment as CommentModel;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ReportTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * The test user.
     *
     * @var Zhiyi\Plus\Models\User
     */
    protected $user;

    protected function setUp()
    {
        parent::setUp();

        $this->user = factory(UserModel::class)->create();
        $this->target_user = factory(UserModel::class)->create();
    }

    /**
     * 测试举报用户.
     *
     * @return void
     * @author BS <414606094@qq.com>
     */
    public function testReportUser()
    {
        $response = $this->actingAs($this->user, 'api')->json('POST', 'api/v2/report/users/'.$this->target_user->id);

        $response->assertStatus(201);
    }

    /**
     * 测试举报评论.
     *
     * @return void
     * @author BS <414606094@qq.com>
     */
    public function testReportComment()
    {
        $comment = factory(CommentModel::class)->create([
            'user_id' => $this->target_user->id,
            'target_user' => $this->user->id,
            'reply_user' => 0,
            'body' => '测试',
            'commentable_id' => 1,
            'commentable_type' => 'system',
        ]);

        $response = $this->actingAs($this->user, 'api')->json('POST', 'api/v2/report/comments/'.$comment->id);

        $response->assertStatus(201);
    }

    protected function tearDown()
    {
        $this->user->forceDelete();
        $this->target_user->forceDelete();

        parent::tearDown();
    }
}
