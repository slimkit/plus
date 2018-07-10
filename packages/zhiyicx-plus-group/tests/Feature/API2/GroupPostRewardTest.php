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
use Zhiyi\PlusGroup\Models\Group as GroupModel;
use Zhiyi\PlusGroup\Models\Category as CateModel;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Zhiyi\PlusGroup\Models\GroupMember as GroupMemberModel;

class GroupPostRewardTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;

    protected $group;

    protected $post;

    protected $other;

    public function setUp()
    {
        parent::setUp();
        $this->user = factory(UserModel::class)->create();
        $this->other = factory(UserModel::class)->create();
        $this->group = $this->createGroupByUser($this->user);
        $this->post = factory(PostModel::class)->create([
            'user_id' => $this->user->id,
            'group_id' => $this->group->id,
        ]);
    }

    /**
     * 帖子打赏.
     *
     * @return mixed
     */
    public function testGroupPostReward()
    {
        config(['plus-group.group_reward.status' => true]);
        $this->other->wallet()->increment('balance', 100);
        $response = $this
            ->actingAs($this->other, 'api')
            ->json('POST', "/api/v2/plus-group/group-posts/{$this->post->id}/rewards", [
                'amount' => 100
            ]);
        $response
            ->assertStatus(201)
            ->assertJsonStructure(['message']);
    }

    /**
     * 新版帖子打赏.
     *
     * @return mixed
     */
    public function testGroupPostNewReward()
    {
        config(['plus-group.group_reward.status' => true]);
        $this->other->currency()->update([
            'sum' => 1000,
        ]);
        $response = $this
            ->actingAs($this->other, 'api')
            ->json('POST', "/api/v2/plus-group/group-posts/{$this->post->id}/new-rewards", [
                'amount' => 1000
            ]);
        $response
            ->assertStatus(201)
            ->assertJsonStructure(['message']);
    }

    /**
     * 帖子打赏列表.
     *
     * @return mixed
     */
    public function testGroupPostRewardList()
    {
        config(['plus-group.group_reward.status' => true]);
        $this->user->NewWallet()->update([
            'balance' => 1000,
            'total_income' => 0,
            'total_expenses' => 0,
        ]);
        $response = $this
            ->actingAs($this->user, 'api')
            ->json('GET', "/api/v2/plus-group/group-posts/{$this->post->id}/rewards", [
                'amount' => 10
            ]);
        $response
            ->assertStatus(200);
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

        $memberModel = new GroupMemberModel();
        $memberModel->user_id = $user->id;
        $memberModel->group_id = $group->id;
        $memberModel->audit = 1;
        $memberModel->role = 'founder';
        $memberModel->save();

        return $group;
    }
}
