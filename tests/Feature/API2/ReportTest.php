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
use Zhiyi\Plus\Models\Comment as CommentModel;
use Zhiyi\Plus\Models\User as UserModel;
use Zhiyi\Plus\Tests\TestCase;

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
