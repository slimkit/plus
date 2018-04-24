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
use Zhiyi\PlusGroup\Models\Group as GroupModel;
use Zhiyi\PlusGroup\Models\Category as CateModel;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Zhiyi\PlusGroup\Models\GroupMember as GroupMemberModel;
use Zhiyi\PlusGroup\Models\GroupRecommend as GroupRecommendModel;

class ManagementGroupMemberTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * 移除圈子成员.
     *
     * @return mixed
     */
    public function testRemoveGroupMember()
    {
        $user = factory(UserModel::class)->create();
        $other = factory(UserModel::class)->create();
        $group = $this->createGroupByUser($user);
        $member = $this->addMemberToGroup($group, $other);

        $response = $this
            ->actingAs($user, 'api')
            ->json('DELETE', "/api/v2/plus-group/groups/{$group->id}/members/$member->id");

        $response
            ->assertStatus(204);
    }

    /**
     * 设置成员为管理员.
     *
     * @return mixed
     */
    public function testSetGroupAdmin()
    {
        $user = factory(UserModel::class)->create();
        $other = factory(UserModel::class)->create();
        $group = $this->createGroupByUser($user);
        $member = $this->addMemberToGroup($group, $other);

        $response = $this
            ->actingAs($user, 'api')
            ->json('PUT', "/api/v2/plus-group/groups/{$group->id}/managers/{$member->id}");

        $response
            ->assertStatus(201)
            ->assertJsonStructure(['message']);
    }

    /**
     * 移除圈子管理员.
     *
     * @return mixed
     */
    public function testRemoveGroupAdmin()
    {
        $user = factory(UserModel::class)->create();
        $other = factory(UserModel::class)->create();
        $group = $this->createGroupByUser($user);
        $member = $this->addMemberToGroup($group, $other, 'administrator');

        $response = $this
            ->actingAs($user, 'api')
            ->json('DELETE', "/api/v2/plus-group/groups/{$group->id}/managers/{$member->id}");

        $response
            ->assertStatus(204);
    }

    /**
     * 添加圈子成员到黑名单.
     *
     * @return mixed
     */
    public function testGroupMemberToBlacklist()
    {
        $user = factory(UserModel::class)->create();
        $other = factory(UserModel::class)->create();
        $group = $this->createGroupByUser($user);
        $member = $this->addMemberToGroup($group, $other);

        $response = $this
            ->actingAs($user, 'api')
            ->json('PUT', "/api/v2/plus-group/groups/{$group->id}/blacklist/{$member->id}");

        $response
            ->assertStatus(201);
    }

    /**
     * 移除圈子黑名单.
     *
     * @return mixed
     */
    public function testRemoveBlacklistGroupMember()
    {
        $user = factory(UserModel::class)->create();
        $other = factory(UserModel::class)->create();
        $group = $this->createGroupByUser($user);
        $member = $this->addMemberToGroup($group, $other, 'member', 1);

        $response = $this
            ->actingAs($user, 'api')
            ->json('DELETE', "/api/v2/plus-group/groups/{$group->id}/blacklist/{$member->id}");

        $response
            ->assertStatus(204);
    }

    /**
     * 审核加圈请求.
     *
     * @return mixed
     */
    public function testAuditJoinGroup()
    {
        $user = factory(UserModel::class)->create();
        $other = factory(UserModel::class)->create();
        $group = $this->createGroupByUser($user);
        $member = $this->addMemberToGroup($group, $other, 'member', 1, 0);

        $response = $this
            ->actingAs($user, 'api')
            ->json('PATCH', "/api/v2/plus-group/groups/{$group->id}/members/{$member->id}/audit", [
                'status' => 1
            ]);

        $response
            ->assertStatus(201);
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

    /**
     * 添加成员到圈子.
     *
     * @param GroupModel $group
     * @param UserModel $user
     * @param string $role
     * @return GroupMemberModel
     */
    protected function addMemberToGroup(
        GroupModel $group,
        UserModel $user,
        $role = 'member',
        $disabled = 0,
        $audit = 1
    ): GroupMemberModel {
        $memberModel = new GroupMemberModel();
        $memberModel->user_id = $user->id;
        $memberModel->group_id = $group->id;
        $memberModel->audit = $audit;
        $memberModel->role = $role;
        $memberModel->disabled = $disabled;
        $memberModel->save();
        $group->increment('users_count');

        return $memberModel;
    }
}
