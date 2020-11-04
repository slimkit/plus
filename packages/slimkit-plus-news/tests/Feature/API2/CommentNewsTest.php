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
use Zhiyi\Plus\Models\Comment as CommentModel;
use Zhiyi\Plus\Models\User as UserModel;
use Zhiyi\Plus\Tests\TestCase;

class CommentNewsTest extends TestCase
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
            'audit_status' => 0,
        ]);
    }

    /**
     * 获取资讯列表.
     *
     * @return mixed
     */
    public function testCommentNews()
    {
        $other = UserModel::factory()->create();
        $response = $this
            ->actingAs($this->user, 'api')
            ->json('POST', "/api/v2/news/{$this->news->id}/comments", [
                'body' => 'test',
                'reply_user' => $other->id,
                'comment_mark' => rand(1000, 9999),
            ]);
        $response
            ->assertStatus(201);
    }

    /**
     * 获取资讯下面的评论列表.
     *
     * @return mixed
     */
    public function testGetNewsComment()
    {
        $response = $this
            ->actingAs($this->user, 'api')
            ->json('GET', "/api/v2/news/{$this->news->id}/comments");
        $response
            ->assertStatus(200)
            ->assertJsonStructure(['pinneds', 'comments']);
    }

    /**
     * 删除资讯下面的评论.
     *
     * @return mixed
     */
    public function testDeleteNewsComment()
    {
        $comment = CommentModel::factory()->create([
            'user_id' => $this->user->id,
            'target_user' => 0,
            'body' => 'test',
            'commentable_id' => $this->news->id,
            'commentable_type' => 'news',
        ]);

        $response = $this
            ->actingAs($this->user, 'api')
            ->json('DELETE', "/api/v2/news/{$this->news->id}/comments/{$comment->id}");
        $response
            ->assertStatus(204);
    }
}
