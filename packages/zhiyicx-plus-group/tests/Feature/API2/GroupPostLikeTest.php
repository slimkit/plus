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
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Zhiyi\PlusGroup\Models\GroupMember as GroupMemberModel;
use Zhiyi\PlusGroup\Models\GroupReport as GroupReportModel;
use Zhiyi\PlusGroup\Models\GroupRecommend as GroupRecommendModel;

class GroupPostLikeTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;

    protected $group;

    protected $post;

    public function setUp()
    {
        parent::setUp();
        $this->user = factory(UserModel::class)->create();
        $this->group = $this->createGroupByUser($this->user);
        $this->post = factory(PostModel::class)->create([
            'user_id' => $this->user->id,
            'group_id' => $this->group->id,
        ]);
    }

    /**
     * 帖子喜欢.
     *
     * @return mixed
     */
    public function testGroupPostLike()
    {
        $response = $this
            ->actingAs($this->user, 'api')
            ->json('POST', "/api/v2/plus-group/group-posts/{$this->post->id}/likes");
        $response
            ->assertStatus(201)
            ->assertJsonStructure(['message']);
    }

    /**
     * 帖子喜欢列表.
     *
     * @return mixed
     */
    public function testGroupPostLikeList()
    {
        $this->post->like($this->user);
        $response = $this
            ->actingAs($this->user, 'api')
            ->json('GET', "/api/v2/plus-group/group-posts/{$this->post->id}/likes");
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                ['id', 'user_id', 'target_user', 'likeable_id', 'likeable_type', 'user']
            ]);
    }

    /**
     * 取消帖子喜欢.
     *
     * @return mixed
     */
    public function testCancelGroupPostLike()
    {
        $this->post->like($this->user);
        
        $response = $this
            ->actingAs($this->user, 'api')
            ->json('DELETE', "/api/v2/plus-group/group-posts/{$this->post->id}/likes");
        $response
            ->assertStatus(204)->dump();
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
