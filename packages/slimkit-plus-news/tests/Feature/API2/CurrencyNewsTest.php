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
use Zhiyi\Plus\Models\Tag as TagModel;
use Zhiyi\Plus\Models\User as UserModel;
use Zhiyi\Plus\Models\Comment as CommentModel;
use Zhiyi\Plus\Models\TagCategory as TagCateModel;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\News as NewsModel;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\NewsCate as NewsCateModel;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\NewsPinned as NewsPinnedModel;

class CurrencyNewsTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * 发布一个资讯.
     *
     * @return mixed
     */
    public function testPublishNews()
    {
        $user = factory(UserModel::class)->create();
        $cate = factory(NewsCateModel::class)->create();

        $response = $this
            ->actingAs($user, 'api')
            ->json('POST', "/api/v2/news/categories/{$cate->id}/currency-news", [
                'title' => 'test',
                'subject' => 'test',
                'content' => 'test',
                'tags' => $this->createTags(),
                'from' => 'test',
                'image' => null,
                'author' => 'test',
                'text_content' => 'test',
            ]);
        $response
            ->assertStatus(201)
            ->assertJsonStructure(['message']);
    }

    /**
     * 创建所需标签.
     *
     * @return mixed
     */
    protected function createTags()
    {
        $cate = factory(TagCateModel::class)->create();
        $tags = factory(TagModel::class, 3)->create([
            'tag_category_id' => $cate->id,
        ]);

        return $tags->pluck('id')->implode(',');
    }

    /**
     * 积分进行申请投稿置顶.
     *
     * @return mixed
     */
    public function testCurrencyPinnedNews()
    {
        $user = factory(UserModel::class)->create();
        $user->currency()->update([
            'sum' => 1000,
            'type' => 1,
        ]);

        $cate = factory(NewsCateModel::class)->create();
        $news = factory(NewsModel::class)->create([
            'title' => 'test',
            'user_id' => $user->id,
            'cate_id' => $cate->id,
            'audit_status' => 1,
        ]);

        $response = $this
            ->actingAs($user, 'api')
            ->json('POST', "/api/v2/news/{$news->id}/currency-pinneds", [
                'amount' => 100,
                'day' => 1,
            ]);
        $response
            ->assertStatus(201)
            ->assertJsonStructure(['message']);
    }

    /**
     * 资讯评论申请置顶.
     *
     * @return mixed
     */
    public function testPinnedNewsComment()
    {
        $user = factory(UserModel::class)->create();
        $other = factory(UserModel::class)->create();
        $other->currency()->update([
            'sum' => 1000,
            'type' => 1,
        ]);

        $cate = factory(NewsCateModel::class)->create();
        $news = factory(NewsModel::class)->create([
            'title' => 'test',
            'user_id' => $user->id,
            'cate_id' => $cate->id,
            'audit_status' => 1,
        ]);

        $comment = factory(CommentModel::class)->create([
            'user_id' => $other->id,
            'target_user' => 0,
            'body' => 'test',
            'commentable_id' => $news->id,
            'commentable_type' => 'news',
        ]);

        $response = $this
            ->actingAs($other, 'api')
            ->json('POST', "/api/v2/news/{$news->id}/comments/{$comment->id}/currency-pinneds", [
                'amount' => 100,
                'day' => 1,
            ]);

        $response
            ->assertStatus(201)
            ->assertJsonStructure(['message']);
    }

    /**
     * 通过审核评论置顶.
     *
     * @return mixed
     */
    public function testAuditNewsCommentPinned()
    {
        $user = factory(UserModel::class)->create();
        $other = factory(UserModel::class)->create();

        $cate = factory(NewsCateModel::class)->create();
        $news = factory(NewsModel::class)->create([
            'title' => 'test',
            'user_id' => $user->id,
            'cate_id' => $cate->id,
            'audit_status' => 1,
        ]);

        $comment = factory(CommentModel::class)->create([
            'user_id' =>    $user->id,
            'target_user' => 0,
            'body' => 'test',
            'commentable_id' => $news->id,
            'commentable_type' => 'news',
        ]);

        $pinned = new NewsPinnedModel();
        $pinned->user_id = $other->id;
        $pinned->raw = $comment->id;
        $pinned->target = $news->id;
        $pinned->channel = 'news:comment';
        $pinned->amount = 1000;
        $pinned->day = 2;
        $pinned->target_user = $news->user_id ?? 0;
        $pinned->state = 0;
        $pinned->save();

        $response = $this
            ->actingAs($user, 'api')
            ->json(
                'PATCH',
                "/api/v2/news/{$news->id}/comments/{$comment->id}/pinneds/{$pinned->id}"
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
        $user = factory(UserModel::class)->create();
        $other = factory(UserModel::class)->create();

        $cate = factory(NewsCateModel::class)->create();
        $news = factory(NewsModel::class)->create([
            'title' => 'test',
            'user_id' => $user->id,
            'cate_id' => $cate->id,
            'audit_status' => 1,
        ]);

        $comment = factory(CommentModel::class)->create([
            'user_id' =>    $user->id,
            'target_user' => 0,
            'body' => 'test',
            'commentable_id' => $news->id,
            'commentable_type' => 'news',
        ]);

        $pinned = new NewsPinnedModel();
        $pinned->user_id = $other->id;
        $pinned->raw = $comment->id;
        $pinned->target = $news->id;
        $pinned->channel = 'news:comment';
        $pinned->amount = 1000;
        $pinned->day = 2;
        $pinned->target_user = $news->user_id ?? 0;
        $pinned->state = 0;
        $pinned->save();

        $response = $this
            ->actingAs($user, 'api')
            ->json(
                'PATCH',
                "/api/v2/news/{$news->id}/comments/{$comment->id}/pinneds/{$pinned->id}/reject"
            );
        $response
            ->assertStatus(204);
    }
}
