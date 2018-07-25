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

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Feature\API2;

use Zhiyi\Plus\Tests\TestCase;
use Zhiyi\Plus\Models\User as UserModel;
use Zhiyi\Plus\Models\Comment as CommentModel;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\News as NewsModel;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\NewsCate as NewsCateModel;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\NewsPinned as NewsPinnedModel;

class PinnedNewsTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;

    protected $cate;

    protected $news;

    public function setUp()
    {
        parent::setUp();
        $this->user = factory(UserModel::class)->create();
        $this->cate = factory(NewsCateModel::class)->create();
        $this->news = factory(NewsModel::class)->create([
            'title' => 'test',
            'user_id' => $this->user->id,
            'cate_id' => $this->cate->id,
            'audit_status' => 1,
        ]);
    }

    public function testPinnedNews()
    {
        $this->user->wallet()->increment('balance', 100);

        $response = $this
            ->actingAs($this->user, 'api')
            ->json('POST', "/api/v2/news/{$this->news->id}/pinneds", [
                'amount' => 100,
                'day' => 1,
            ]);
        $response
            ->assertStatus(201)
            ->assertJsonStructure(['message']);
    }

    /**
     * 查看申请置顶的资讯列表.
     *
     * @return mixed
     */
    public function testGetNewsPinneds()
    {
        $response = $this
            ->actingAs($this->user, 'api')
            ->json('GET', '/api/v2/news/pinneds');
        $response
            ->assertStatus(200);
    }

    /**
     * 测试新版的评论置顶请求
     * @Author   Wayne
     * @DateTime 2018-04-24
     * @Email    qiaobin@zhiyicx.com
     * @return   [type]              [description]
     */
    public function testNewPinnedNewsComment()
    {
        $other = factory(UserModel::class)->create();
        $other->currency()->increment('sum', 100);
        $comment = factory(CommentModel::class)->create([
            'user_id' =>    $other->id,
            'target_user' => 0,
            'body' => 'test',
            'commentable_id' => $this->news->id,
            'commentable_type' => 'news',
        ]);

        $response = $this
            ->actingAs($other, 'api')
            ->json('POST', "/api/v2/news/{$this->news->id}/comments/{$comment->id}/currency-pinneds", [
                'amount' => 100,
                'day' => 1,
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
        $other = factory(UserModel::class)->create();
        $other->wallet()->increment('balance', 100);

        $comment = factory(CommentModel::class)->create([
            'user_id' =>    $other->id,
            'target_user' => 0,
            'body' => 'test',
            'commentable_id' => $this->news->id,
            'commentable_type' => 'news',
        ]);

        $pinned = new NewsPinnedModel();
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
                "/api/v2/news/{$this->news->id}/comments/{$comment->id}/pinneds/{$pinned->id}"
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
        $other = factory(UserModel::class)->create();
        $other->wallet()->increment('balance', 100);

        $comment = factory(CommentModel::class)->create([
            'user_id' =>    $other->id,
            'target_user' => 0,
            'body' => 'test',
            'commentable_id' => $this->news->id,
            'commentable_type' => 'news',
        ]);

        $pinned = new NewsPinnedModel();
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
                "/api/v2/news/{$this->news->id}/comments/{$comment->id}/pinneds/{$pinned->id}/reject"
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
        $other = factory(UserModel::class)->create();
        $other->wallet()->increment('balance', 100);

        $comment = factory(CommentModel::class)->create([
            'user_id' => $other->id,
            'target_user' => 0,
            'body' => 'test',
            'commentable_id' => $this->news->id,
            'commentable_type' => 'news',
        ]);

        $pinned = new NewsPinnedModel();
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
