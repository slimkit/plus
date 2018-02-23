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

class ReportGroupTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * 测试获取圈子分类接口.
     *
     * @return mixed
     */
    public function testGetReports()
    {
        $user = factory(UserModel::class)->create();
        $group = $this->createGroupByUser($user);

        $response = $this
            ->actingAs($user, 'api')
            ->json('GET', '/api/v2/plus-group/reports?group_id='.$group->id);
        $response
            ->assertStatus(200)
            ->assertJsonStructure([]);
    }

    /**
     * 测试帖子举报接口.
     *
     * @return mixed
     */
    public function testReportGroupPost()
    {
        $user = factory(UserModel::class)->create();
        $other = factory(UserModel::class)->create();

        $group = $this->createGroupByUser($user);
        $post = factory(PostModel::class)->create([
           'user_id' => $user->id,
           'group_id' => $group->id,
        ]);

        $response = $this
            ->actingAs($other, 'api')
            ->json('POST', "/api/v2/plus-group/reports/posts/{$post->id}", [
                'content' => 'test',
            ]);
        $response
            ->assertStatus(201)
            ->assertJsonStructure(['message']);
    }

    /**
     * 举报圈子.
     *
     * @return mixed
     */
    public function testReportGroupTest()
    {

        $user = factory(UserModel::class)->create();
        $other = factory(UserModel::class)->create();
        $group = $this->createGroupByUser($user);

        $response = $this
            ->actingAs($other, 'api')
            ->json('POST', "/api/v2/plus-group/groups/{$group->id}/reports", [
                'content' => 'test',
            ]);
        $response
            ->assertStatus(201)
            ->assertJsonStructure(['message']);
    }


    /**
     * 举报圈子帖子评论.
     *
     * @return mixed
     */
    public function testReportGroupPostCommentTest()
    {

        $user = factory(UserModel::class)->create();
        $other = factory(UserModel::class)->create();
        $group = $this->createGroupByUser($user);
        $post = factory(PostModel::class)->create([
            'user_id' => $user->id,
            'group_id' => $group->id,
        ]);
        $comment = factory(CommentModel::class)->create([
            'user_id' => $user->id,
            'target_user' => 0,
            'body' => 'test',
            'commentable_id' => $post->id,
            'commentable_type' => 'group-posts',
        ]);

        $response = $this
            ->actingAs($other, 'api')
            ->json('POST', "/api/v2/plus-group/reports/comments/{$comment->id}", [
                'content' => 'test',
            ]);
        $response
            ->assertStatus(201)
            ->assertJsonStructure(['message']);
    }

    /**
     * 举报圈子帖子审核驳回.
     *
     * @return mixed
     */
    public function testRejectReportGroupPostTest()
    {

        $user = factory(UserModel::class)->create();
        $other = factory(UserModel::class)->create();
        $group = $this->createGroupByUser($user);
        $post = factory(PostModel::class)->create([
            'user_id' => $user->id,
            'group_id' => $group->id,
        ]);
        $report = new GroupReportModel();
        $report->user_id = $other->id;
        $report->target_id = $user->id;
        $report->group_id = $group->id;
        $report->resource_id = $post->id;
        $report->content = 'test';
        $report->type = 'post';
        $report->status = 0;
        $report->save();


        $response = $this
            ->actingAs($user, 'api')
            ->json('PATCH', "/api/v2/plus-group/reports/{$report->id}/reject");
        $response
            ->assertStatus(201)
            ->assertJsonStructure(['message']);
    }

    /**
     * 举报圈子帖子审核通过.
     *
     * @return mixed
     */
    public function testAcceptReportGroupPostTest()
    {

        $user = factory(UserModel::class)->create();
        $other = factory(UserModel::class)->create();
        $group = $this->createGroupByUser($user);
        $post = factory(PostModel::class)->create([
            'user_id' => $user->id,
            'group_id' => $group->id,
        ]);
        $report = new GroupReportModel();
        $report->user_id = $other->id;
        $report->target_id = $user->id;
        $report->group_id = $group->id;
        $report->resource_id = $post->id;
        $report->content = 'test';
        $report->type = 'post';
        $report->status = 0;
        $report->save();


        $response = $this
            ->actingAs($user, 'api')
            ->json('PATCH', "/api/v2/plus-group/reports/{$report->id}/accept");
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
