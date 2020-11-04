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

namespace SlimKit\PlusFeed\Tests\Feature\API2;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed;
use Zhiyi\Plus\Models\Comment as CommentModel;
use Zhiyi\Plus\Models\User as UserModel;
use Zhiyi\Plus\Tests\TestCase;

class CurrencyPinnedFeedCommentTest extends TestCase
{
    use DatabaseTransactions;

    protected $owner;

    protected $other;

    protected $feed;

    protected $comment;

    public function setUp()
    {
        parent::setUp();

        $this->owner = UserModel::factory()->create();

        $this->other = UserModel::factory()->create([
            'password' => bcrypt('123456'),
        ]);

        $this->feed = Feed::factory()->create([
            'user_id' => $this->owner->id,
        ]);

        $this->comment = CommentModel::factory()->create([
            'user_id' => $this->other->id,
            'target_user' => $this->other->id,
            'body' => 'test',
            'commentable_id' => $this->feed->id,
            'commentable_type' => 'feeds',
        ]);
    }

    /**
     * 积分申请动态评论置顶.
     *
     * @return mixed
     */
    public function testPinnedFeedComment()
    {
        $this->other->currency()->update([
            'sum' => 1000,
            'type' => 1,
        ]);

        $response = $this
            ->actingAs($this->other, 'api')
            ->json('POST', "/api/v2/feeds/{$this->feed->id}/comments/{$this->comment->id}/currency-pinneds", [
                'amount' => 100,
                'day' => 1,
                'password' => '123456',
            ]);
        $response
            ->assertStatus(201)
            ->assertJsonStructure(['message']);
    }
}
