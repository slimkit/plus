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

class GroupPostTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * 圈子帖子列表.
     *
     * @return mixed
     */
    public function testGetGroupPosts()
    {
        $user = factory(UserModel::class)->create();
        $other = factory(UserModel::class)->create();
        $group = $this->createGroupByUser($user);
        $post = factory(PostModel::class, 3)->create([
            'user_id' => $user->id,
            'group_id' => $group->id,
        ]);

        $response = $this
            ->actingAs($user, 'api')
            ->json('GET', "/api/v2/plus-group/groups/{$group->id}/posts");
        $response
            ->assertStatus(200)
            ->assertJsonStructure(['pinneds', 'posts']);
    }

    /**
     * 圈子帖子列表.
     *
     * @return mixed
     */
    public function testGetGroupPostDetail()
    {
        $user = factory(UserModel::class)->create();
        $other = factory(UserModel::class)->create();
        $group = $this->createGroupByUser($user);
        $post = factory(PostModel::class)->create([
            'user_id' => $user->id,
            'group_id' => $group->id,
        ]);

        $response = $this
            ->actingAs($user, 'api')
            ->json('GET', "/api/v2/plus-group/groups/{$group->id}/posts/{$post->id}");
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'id', 'user_id', 'group_id', 'title', 'body', 'summary', 'likes_count',
                'comments_count', 'views_count', 'liked', 'created_at', 'updated_at',
                'collected', 'reward_amount', 'reward_number', 'group'
            ]);
    }

    /**
     * 发帖.
     *
     * @return mixed
     */
    public function testCreateGroupPost()
    {
        $user = factory(UserModel::class)->create();
        $group = $this->createGroupByUser($user);

        $response = $this
            ->actingAs($user, 'api')
            ->json('POST', "/api/v2/plus-group/groups/{$group->id}/posts", [
                'title' => 'test',
                'body' => 'test',
                'summary' => 'test',
                'sync_feed' => 1,
                'feed_from' => 5
            ]);
        $response
            ->assertStatus(201)
            ->assertJsonStructure(['message', 'post']);
    }

    /**
     * 修改发帖.
     *
     * @return mixed
     */
    public function testUpdateGroupPost()
    {
        $user = factory(UserModel::class)->create();
        $group = $this->createGroupByUser($user);
        $post = factory(PostModel::class)->create([
            'user_id' => $user->id,
            'group_id' => $group->id,
        ]);

        $response = $this
            ->actingAs($user, 'api')
            ->json('PUT', "/api/v2/plus-group/groups/{$group->id}/posts/{$post->id}", [
                'title' => 'test',
                'body' => 'test',
                'summary' => 'test'
            ]);
        $response
            ->assertStatus(201)
            ->assertJsonStructure(['message', 'post']);
    }

    /**
     * 删除帖子.
     *
     * @return mixed
     */
    public function testDeleteGroupPost()
    {
        $user = factory(UserModel::class)->create();
        $group = $this->createGroupByUser($user);
        $post = factory(PostModel::class)->create([
            'user_id' => $user->id,
            'group_id' => $group->id,
        ]);
        $group->increment('posts_count', 1);

        $response = $this
            ->actingAs($user, 'api')
            ->json('DELETE', "/api/v2/plus-group/groups/{$group->id}/posts/{$post->id}");
        $response
            ->assertStatus(204);
    }

    /**
     * 我的帖子列表.
     *
     * @return mixed
     */
    public function testMyGroupPost()
    {
        $user = factory(UserModel::class)->create();
        $group = $this->createGroupByUser($user);
        $post = factory(PostModel::class, 3)->create([
            'user_id' => $user->id,
            'group_id' => $group->id,
        ]);
        $group->increment('posts_count', 1);

        $response = $this
            ->actingAs($user, 'api')
            ->json('GET', "/api/v2/plus-group/user-group-posts");
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                [
                    'id',
                    'user_id',
                    'group_id',
                    'title',
                    'summary',
                    'likes_count',
                    'comments_count',
                    'views_count',
                    'created_at',
                    'updated_at',
                    'collected',
                    'liked',
                    'comments',
                    'images',
                    'user',
                    'group'
                ]
            ]);
    }

    /**
     * 全部帖子列表.
     *
     * @return mixed
     */
    public function testGetAllGroupPost()
    {
        $user = factory(UserModel::class)->create();
        $group = $this->createGroupByUser($user);
        $post = factory(PostModel::class, 3)->create([
            'user_id' => $user->id,
            'group_id' => $group->id,
        ]);
        $group->increment('posts_count', 1);

        $response = $this
            ->actingAs($user, 'api')
            ->json('GET', "/api/v2/plus-group/group-posts");
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                [
                    'id',
                    'user_id',
                    'group_id',
                    'title',
                    'summary',
                    'likes_count',
                    'comments_count',
                    'views_count',
                    'created_at',
                    'updated_at',
                    'collected',
                    'liked',
                    'comments',
                    'images',
                    'user',
                    'group'
                ]
            ]);
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
