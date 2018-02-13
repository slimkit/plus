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

class JoinGroupTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * 加入公开圈.
     *
     * @return mixed
     */
    public function testJoinPublicGroup()
    {
        $other = factory(UserModel::class)->create();
        $user = factory(UserModel::class)->create();
        $group = $this->createGroupByUser($user);

        $response = $this
            ->actingAs($other, 'api')
            ->json('PUT', "/api/v2/plus-group/currency-groups/{$group->id}");
        $response
            ->assertStatus(201)
            ->assertJsonStructure(['message']);
    }

    /**
     * 加入私密圈.
     *
     * @return mixed
     */
    public function testJoinPrivateGroup()
    {
        $other = factory(UserModel::class)->create();
        $user = factory(UserModel::class)->create();
        $group = $this->createGroupByUser($user);
        $response = $this
            ->actingAs($other, 'api')
            ->json('PUT', "/api/v2/plus-group/currency-groups/{$group->id}");
        $response
            ->assertStatus(201)
            ->assertJsonStructure(['message']);
    }

    /**
     * 加入收费私密圈.
     *
     * @return mixed
     */
    public function testJoinPaidGroup()
    {
        $user = factory(UserModel::class)->create();
        $other = factory(UserModel::class)->create();
        $other->currency()->firstOrCreate(
            ['type' => 1],
            ['sum' => 1000]
        );
        $group = $this->createGroupByUser($user);
        $response = $this
            ->actingAs($other, 'api')
            ->json('PUT', "/api/v2/plus-group/currency-groups/{$group->id}");
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
    protected function createGroupByUser(UserModel $user, $mode='public'): GroupModel
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
