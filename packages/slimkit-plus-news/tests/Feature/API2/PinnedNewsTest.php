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

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Feature\API2;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\News as NewsModel;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\NewsCate as NewsCateModel;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\NewsPinned;
use Zhiyi\Plus\Models\Comment as CommentModel;
use Zhiyi\Plus\Models\User as UserModel;
use Zhiyi\Plus\Tests\TestCase;

class PinnedNewsTest extends TestCase
{
    use DatabaseTransactions;
    protected $user;
    protected $cate;
    protected $news;

    public function setUp()
    {
        parent::setUp();
        $this->user = UserModel::factory()->create();
        $this->cate = NewsCateModel::factory()->create();
        $this->news = NewsModel::factory()->create([
            'title' => 'test',
            'user_id' => $this->user->id,
            'cate_id' => $this->cate->id,
            'audit_status' => 1,
        ]);
    }

    /**
     * 测试新版的评论置顶请求
     *
     * @Author   Wayne
     * @DateTime 2018-04-24
     * @Email    qiaobin@zhiyicx.com
     * @return void [type]
     */
    public function testNewPinnedNewsComment()
    {
        $other = UserModel::factory()->create([
            'password' => bcrypt('123456'),
        ]);
        $other->currency()->increment('sum', 100);
        $comment = CommentModel::factory()->create([
            'user_id' =>    $other->id,
            'target_user' => 0,
            'body' => 'test',
            'commentable_id' => $this->news->id,
            'commentable_type' => 'news',
        ]);

        $response = $this
            ->actingAs($other, 'api')
            ->json('POST', "/api/v2/news/{$this->news->id}/comments/{$comment->id}/currency-pinneds", [
                'amount'   => 100,
                'day'      => 1,
                'password' => '123456',
            ]);
        $response
            ->assertStatus(201)
            ->assertJsonStructure(['message']);
    }

    /**
     * 查看资讯中申请置顶的评论列表.
     *
     * @return mixed
     */
    public function testGetNewsCommentPinneds()
    {
        $response = $this
            ->actingAs($this->user, 'api')
            ->json('GET', '/api/v2/news/comments/pinneds');
        $response
            ->assertStatus(200);
    }

    /**
     * 通过审核评论置顶.
     *
     * @return mixed
     */
    public function testAuditNewsCommentPinned()
    {
        $other = UserModel::factory()->create();
        $other->wallet()->increment('balance', 100);

        $comment = CommentModel::factory()->create([
            'user_id' =>    $other->id,
            'target_user' => 0,
            'body' => 'test',
            'commentable_id' => $this->news->id,
            'commentable_type' => 'news',
        ]);

        $pinned = new NewsPinned();
        $pinned->user_id = $other->id;
        $pinned->raw = $comment->id;
        $pinned->target = $this->news->id;
        $pinned->channel = 'news:comment';
        $pinned->amount = 1000;
        $pinned->day = 2;
        $pinned->target_user = $this->news->user_id ?? 0;
        $pinned->state = 0;
        $pinned->save();

        $response = $this
            ->actingAs($this->user, 'api')
            ->json(
                'PATCH',
                "/api/v2/news/{$this->news->id}/comments/{$comment->id}/currency-pinneds/{$pinned->id}"
            );
        $response
            ->assertStatus(201)
            ->assertJsonStructure(['message']);
    }

    /**
     * 拒绝审核评论置顶.
     *
     * @return mixed
     */
    public function testRejectNewsCommentPinned()
    {
        $other = UserModel::factory()->create();
        $other->wallet()->increment('balance', 100);

        $comment = CommentModel::factory()->create([
            'user_id' =>    $other->id,
            'target_user' => 0,
            'body' => 'test',
            'commentable_id' => $this->news->id,
            'commentable_type' => 'news',
        ]);

        $pinned = new NewsPinned();
        $pinned->user_id = $other->id;
        $pinned->raw = $comment->id;
        $pinned->target = $this->news->id;
        $pinned->channel = 'news:comment';
        $pinned->amount = 1000;
        $pinned->day = 2;
        $pinned->target_user = $this->news->user_id ?? 0;
        $pinned->state = 0;
        $pinned->save();

        $response = $this
            ->actingAs($this->user, 'api')
            ->json(
                'PATCH',
                "/api/v2/news/{$this->news->id}/comments/{$comment->id}/currency-pinneds/{$pinned->id}/reject"
            );
        $response
            ->assertStatus(204);
    }

    /**
     * 取消评论置顶.
     *
     * @return mixed
     */
    public function testCancelNewsCommentPinned()
    {
        $other = UserModel::factory()->create();
        $other->wallet()->increment('balance', 100);

        $comment = CommentModel::factory()->create([
            'user_id' => $other->id,
            'target_user' => 0,
            'body' => 'test',
            'commentable_id' => $this->news->id,
            'commentable_type' => 'news',
        ]);

        $pinned = new NewsPinned();
        $pinned->user_id = $other->id;
        $pinned->raw = $comment->id;
        $pinned->target = $this->news->id;
        $pinned->channel = 'news:comment';
        $pinned->amount = 1000;
        $pinned->day = 2;
        $pinned->target_user = $this->news->user_id ?? 0;
        $pinned->state = 1;
        $pinned->save();

        $response = $this
            ->actingAs($this->user, 'api')
            ->json(
                'DELETE',
                "/api/v2/news/{$this->news->id}/comments/{$comment->id}/pinneds/{$pinned->id}"
            );
        $response
            ->assertStatus(204);
    }
}
