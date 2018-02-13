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
use Illuminate\Foundation\Testing\TestResponse;
use Zhiyi\PlusGroup\Models\Group as GroupModel;
use Zhiyi\PlusGroup\Models\Category as CateModel;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Zhiyi\PlusGroup\Models\GroupMember as GroupMemberModel;
use Zhiyi\PlusGroup\Models\GroupRecommend as GroupRecommendModel;

class AuditJoinGroupTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;

    protected $other;

    public function setUp()
    {
        parent::setUp();
        $this->user = factory(UserModel::class)->create();
        $this->other = factory(UserModel::class)->create();
        $this->other->currency()->firstOrCreate(
            ['type' => 1],
            ['sum' => 1000]
        );
    }

    /**
     * 创建圈子.
     *
     * @param UserModel $user
     * @return GroupModel
     */
    protected function createGroup(UserModel $user, $mode='public'): GroupModel
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

    /**
     * @param UserModel $user
     * @param GroupModel $group
     * @return mixed
     */
    protected function createMember(UserModel $user, GroupModel $group): GroupMemberModel
    {
        $member = new GroupMemberModel();
        $member->user_id = $user->id;
        $member->audit = 0;
        $member->role = 'member';
        $group->members()->save($member);

        return $member;
    }

    /**
     * @param UserModel $user
     * @param GroupMemberModel $member
     * @param GroupModel $group
     * @param string $status
     * @return
     */
    protected function sendPatchRequest(
        UserModel $user,
        GroupMemberModel $member,
        GroupModel $group,
        int $status = 1): TestResponse
    {
        $response = $this
            ->actingAs($user, 'api')
            ->json('PATCH', "/api/v2/plus-group/currency-groups/{$group->id}/members/{$member->id}/audit", [
                'status' => $status,
            ]);
        return $response;
    }

    /**
     * 私密圈审核通过.
     *
     * @return mixed
     */
    public function testPassJoinPrivateGroup()
    {
        $group = $this->createGroup($this->user, 'private');
        $member = $this->createMember($this->other, $group);

        $this
            ->sendPatchRequest($this->user, $member, $group, 1)
            ->assertStatus(201)
            ->assertJsonStructure(['message']);
    }

    /**
     * 私密圈审核拒绝.
     *
     * @return mixed
     */
    public function testRejectJoinPrivateGroup()
    {
        $group = $this->createGroup($this->user, 'private');
        $member = $this->createMember($this->other, $group);

        $this
            ->sendPatchRequest($this->user, $member, $group, 2)
            ->assertStatus(201)
            ->assertJsonStructure(['message']);
    }

    /**
     * 收费圈审核通过.
     *
     * @return mixed
     */
    public function testPassJoinPaidGroup()
    {
        $group = $this->createGroup($this->user, 'paid');
        $member = $this->createMember($this->other, $group);

        $this
            ->sendPatchRequest($this->user, $member, $group, 1)
            ->assertStatus(201)
            ->assertJsonStructure(['message']);
    }

    /**
     * 收费圈审核拒绝.
     *
     * @return mixed
     */
    public function testRejectJoinPaidPaidGroup()
    {
        $group = $this->createGroup($this->user, 'paid');
        $member = $this->createMember($this->other, $group);

        $this
            ->sendPatchRequest($this->user, $member, $group, 2)
            ->assertStatus(201)
            ->assertJsonStructure(['message']);
    }
}
