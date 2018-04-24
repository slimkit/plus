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
use Zhiyi\Plus\Models\User as UserModel;
use Zhiyi\PlusGroup\Models\Post as PostModel;
use Zhiyi\Plus\Models\Comment as CommentModel;
use Zhiyi\PlusGroup\Models\Group as GroupModel;
use Zhiyi\PlusGroup\Models\Category as CateModel;
use Zhiyi\PlusGroup\Models\Pinned as PinnedModel;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Zhiyi\PlusGroup\Models\GroupMember as GroupMemberModel;

class GroupPostPinnedTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;

    protected $other;

    protected $group;

    protected $post;

    protected $comment;

    public function setUp()
    {
        parent::setUp();
        $this->user = factory(UserModel::class)->create();
        $this->other = factory(UserModel::class)->create();
        $this->group = $this->createGroupByUser($this->user);
        $this->addMemberToGroup($this->user, $this->group);
        $this->addMemberToGroup($this->other, $this->group, 'member');
        $this->post = factory(PostModel::class)->create([
            'user_id' => $this->user->id,
            'group_id' => $this->group->id,
        ]);
        $this->comment = factory(CommentModel::class)->create([
            'user_id' => $this->other->id,
            'target_user' => 0,
            'body' => 'test',
            'commentable_id' => $this->post->id,
            'commentable_type' => 'group-posts',
        ]);
        $this->post->increment('comments_count');
    }

    /**
     * 申请帖子置顶.
     *
     * @return mixed
     */
    public function testGroupPostCommentPinned()
    {
        $this->other->currency()->update(
            ['sum' => 1000]
        );
        $response = $this
            ->actingAs($this->other, 'api')
            ->json('POST', "/api/v2/plus-group/currency-pinned/comments/{$this->comment->id}", [
                'day' => 10,
                'amount' => 20
            ]);
        $response
            ->assertStatus(201)
            ->assertJsonStructure(['message']);
    }

    /**
     * 通过帖子评论置顶.
     *
     * @return mixed
     */
    public function testAcceptGroupPostCommentPinned()
    {
        $pinnedModel = new PinnedModel();
        $pinnedModel->channel = 'comment';
        $pinnedModel->raw = $this->post->id;
        $pinnedModel->target = $this->comment->id;
        $pinnedModel->user_id = $this->other->id;
        $pinnedModel->target_user = $this->user->id;
        $pinnedModel->amount = 10;
        $pinnedModel->day = 1;
        $pinnedModel->status = 0;
        $pinnedModel->save();

        $response = $this
            ->actingAs($this->user, 'api')
            ->json('PATCH', "/api/v2/plus-group/currency-pinned/comments/{$this->comment->id}/accept");

        $response
            ->assertStatus(201)
            ->assertJsonStructure(['message']);
    }

    /**
     * 拒绝帖子评论置顶.
     *
     * @return mixed
     */
    public function testRejectGroupPostCommentPinned()
    {
        $pinnedModel = new PinnedModel();
        $pinnedModel->channel = 'comment';
        $pinnedModel->raw = $this->post->id;
        $pinnedModel->target = $this->comment->id;
        $pinnedModel->user_id = $this->other->id;
        $pinnedModel->target_user = $this->user->id;
        $pinnedModel->amount = 10;
        $pinnedModel->day = 1;
        $pinnedModel->status = 0;
        $pinnedModel->save();

        $response = $this
            ->actingAs($this->user, 'api')
            ->json('PATCH', "/api/v2/plus-group/currency-pinned/comments/{$this->comment->id}/reject");

        $response
            ->assertStatus(201)
            ->assertJsonStructure(['message']);
    }

    /**
     * 创建圈子.
     *
     * @param UserModel $user
     * @return GroupModel
     */
    protected function createGroupByUser(UserModel $user, $mode = 'public'): GroupModel
    {
        $cate = factory(CateModel::class)->create();
        $group = factory(GroupModel::class)->create([
            'user_id' => $user->id,
            'category_id' => $cate->id,
            'mode' => $mode,
            'money' => $mode == 'paid' ? 10 : 0,
        ]);

        return $group;
    }

    /**
     * add member to group.
     *
     * @param UserModel $user
     * @param GroupModel $group
     * @param string $role
     *
     * @return mixed
     */
    protected function addMemberToGroup(UserModel $user, GroupModel $group, $role = 'founder')
    {
        $memberModel = new GroupMemberModel();
        $memberModel->user_id = $user->id;
        $memberModel->group_id = $group->id;
        $memberModel->audit = 1;
        $memberModel->role = $role;
        $memberModel->save();
    }
}
