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

class GetGroupTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * 断言结构.
     *
     * @var array
     */
    protected $structure = [
        'id',
        'name',
        'user_id',
        'category_id',
        'mode',
        'permissions',
        'summary',
    ];

    /**
     * 测试获取圈子分类接口.
     *
     * @return mixed
     */
    public function testGetGroupByCategory()
    {
        $cate = factory(CateModel::class)->create();
        $user = factory(UserModel::class)->create();
        $group = factory(GroupModel::class)->create([
            'user_id' => $user->id,
            'category_id' => $cate->id
        ]);

        $response = $this
            ->actingAs($user, 'api')
            ->json('GET', "/api/v2/plus-group/categories/{$cate->id}/groups");
        $response
            ->assertStatus(200)
            ->assertJsonStructure([$this->structure]);
    }

    /**
     * 获取全部全部接口.
     *
     * @return mixed
     */
    public function testGetGroupAll()
    {
        $cate = factory(CateModel::class)->create();
        $user = factory(UserModel::class)->create();
        $group = factory(GroupModel::class)->create([
            'user_id' => $user->id,
            'category_id' => $cate->id
        ]);

        $response = $this
            ->actingAs($user, 'api')
            ->json('GET', "/api/v2/plus-group/groups");
        $response
            ->assertStatus(200)
            ->assertJsonStructure([$this->structure]);
    }

    /**
     * 获取推荐圈子列表.
     *
     * @return mixed
     */
    public function testGetRecommendGroups()
    {
        $cate = factory(CateModel::class)->create();
        $user = factory(UserModel::class)->create();
        $group = factory(GroupModel::class)->create([
            'user_id' => $user->id,
            'category_id' => $cate->id
        ]);

        $recommend = new GroupRecommendModel();
        $recommend->referrer = $user->id;
        $recommend->disable = 0;
        $recommend->group_id = $group->id;
        $recommend->save();

        $response = $this
            ->actingAs($user, 'api')
            ->json('GET', "/api/v2/plus-group/recommend/groups");
        $response
            ->assertStatus(200)
            ->assertJsonStructure([$this->structure]);
    }

    /**
     * 我的圈子列表.
     *
     * @return mixed
     */
    public function testGetMyGroups()
    {
        $cate = factory(CateModel::class)->create();
        $user = factory(UserModel::class)->create();
        $group = factory(GroupModel::class)->create([
            'user_id' => $user->id,
            'category_id' => $cate->id
        ]);

        $memberModel = new GroupMemberModel();
        $memberModel->user_id = $user->id;
        $memberModel->group_id = $group->id;
        $memberModel->audit = 1;
        $memberModel->role = 'administrator';
        $memberModel->save();

        $response = $this
            ->actingAs($user, 'api')
            ->json('GET', "/api/v2/plus-group/user-groups");
        $response
            ->assertStatus(200)
            ->assertJsonStructure([$this->structure]);
    }

    /**
     * 获取圈子详情.
     *
     * @return mixed
     */
    public function testGetGroupDetail()
    {
        $cate = factory(CateModel::class)->create();
        $user = factory(UserModel::class)->create();
        $group = factory(GroupModel::class)->create([
            'user_id' => $user->id,
            'category_id' => $cate->id
        ]);

        $response = $this
            ->actingAs($user, 'api')
            ->json('GET', "/api/v2/plus-group/groups/{$group->id}");
        $response
            ->assertStatus(200)
            ->assertJsonStructure($this->structure);
    }

    /**
     * 获取圈子总数.
     *
     * @return mixed
     */
    public function testGetGroupCount()
    {
        $cate = factory(CateModel::class)->create();
        $user = factory(UserModel::class)->create();
        $group = factory(GroupModel::class)->create([
            'user_id' => $user->id,
            'category_id' => $cate->id
        ]);

        $response = $this
            ->actingAs($user, 'api')
            ->json('GET', "/api/v2/plus-group/groups/count");
        $response
            ->assertStatus(200)
            ->assertJsonStructure(['count']);
    }
}
