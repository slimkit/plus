<?php

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

use Illuminate\Support\Facades\Route;
use Zhiyi\PlusGroup\API\Controllers as API;
use Illuminate\Contracts\Routing\Registrar as RouteRegisterContract;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'api/v2'], function (RouteRegisterContract $api) {

    // plus-group
    $api->group(['prefix' => 'plus-group'], function (RouteRegisterContract $api) {

        // Group categories.
        // @Route /api/v2/plus-group
        $api->group(['prefix' => '/categories'], function (RouteRegisterContract $api) {

            // List all group categories.
            // @get /api/v2/plus-group/categories
            $api->get('/', API\CategoriesController::class.'@index');

            // List groups of category.
            // @get /api/v2/plus-group/categories/:category/groups
            $api->get('/{category}/groups', API\GroupsController::class.'@index');
        });

        // Group routes
        $api->group(['prefix' => '/groups'], function (RouteRegisterContract $api) {

            // 圈子数量统计
            // @get /api/plus-group/groups/count
            $api->get('/count', API\GroupsController::class.'@count');

            // get protocol of group
            // @get /api/v2/plus-group/groups/protocol
            $api->get('/protocol', API\GroupsController::class.'@protocol');

            // 查看用户相关的圈子.
            // @get /api/v2/plus-group/groups/othersGroups
            $api->get('/users', API\GroupsController::class.'@othersGroups');

            // get groups.
            // @get /api/v2/plus-group/groups
            $api->get('/', API\GroupsController::class.'@groups');

            // get a group info.
            // @get /api/v2/plus-group/groups/:group
            $api->get('/{group}', API\GroupsController::class.'@show')->where(['group' => '[0-9]+']);

            // get list of posts.
            // @get /api/v2/plus-group/groups/:group/posts
            $api->get('/{group}/posts', API\PostController::class.'@index');

            // get a post of group.
            // @post /api/v2/plus-group/groups/:group/posts
            $api->get('/{group}/posts/{post}', API\PostController::class.'@show');

            // get list of members.
            // @get /api/v2/plus-group/groups/:group/members
            $api->get('/{group}/members', API\GroupMemberController::class.'@index');
        });

        $api->group(['prefix' => 'recommend'], function (RouteRegisterContract $api) {

            // get recommends groups
            // @get /api/v2/plus-group/recommend/groups
            $api->get('/groups', API\RecommendController::class.'@groups');
        });

        $api->group(['prefix' => 'round'], function (RouteRegisterContract $api) {

            // get around groups
            // @get /api/v2/plus-group/round/groups
            $api->get('/groups', API\GroupsController::class.'@rounds');
        });

        // post route.
        $api->group(['prefix' => '/group-posts'], function (RouteRegisterContract $api) {

            // get group posts.
            // @get /api/v2/plus-group/groups
            $api->get('/', API\PostController::class.'@posts');

            // get a list of likes.
            // @get /api/v2/plus-group/group-posts/:post/likes
            $api->get('/{post}/likes', API\PostLikeController::class.'@index');

            // get a list of rewards.
            // @get /api/v2/plus-group/group-posts/:post/rewards
            $api->get('/{post}/rewards', API\PostRewardController::class.'@index');

            // get a list of comments.
            // @get /api/v2/plus-group/group-posts/:post/comments
            $api->get('/{post}/comments', API\PostCommentController::class.'@get');
        });

        // Auth routes.
        $api->group(['middleware' => 'auth:api'], function (RouteRegisterContract $api) {
            $api->group(['prefix' => '/categories'], function (RouteRegisterContract $api) {

                // Create a group.
                // @post /api/plus-group/categories/:category/group
                $api->post('/{category}/groups', API\GroupsController::class.'@store')->middleware('sensitive:name,summary,notice');
            });

            // Group auth routes.
            $api->group(['prefix' => '/groups'], function (RouteRegisterContract $api) {

                // Update a group;
                // @post /api/plus-group/groups/:group
                $api->post('/{group}', API\GroupsController::class.'@update')->middleware('sensitive:name,summary,notice');

                // Join a group.
                // @put /api/v2/plus-group/groups/:group
                $api->put('/{group}', API\GroupsController::class.'@join');

                // 退出圈子
                $api->delete('/{group}/exit', API\GroupsController::class.'@exitGroup');

                // incomes of a group
                // @get /api/v2/plus-group/groups/:group/incomes
                $api->get('/{group}/incomes', API\GroupsController::class.'@incomes');

                // Report a group
                // @get /api/v2/plus-group/groups/:group/reports
                $api->post('/{group}/reports', API\ReportController::class.'@group');

                // Send a group post.
                // @post /api/v2/plus-group/groups/:group/posts
                $api->post('/{group}/posts', API\PostController::class.'@store')->middleware('sensitive:title,summary,body');

                // Update a group post.
                // @put @get /api/v2/plus-group/groups/:group/posts/:post
                $api->put('/{group}/posts/{post}', API\PostController::class.'@update');

                // delete a group post.
                // @post /api/v2/plus-group/groups/:group/posts/:post
                $api->delete('/{group}/posts/{post}', API\PostController::class.'@delete');

                // remove members of group
                // @delete /api/v2/plus-group/groups/:group/members/:member
                $api->delete('/{group}/members/{member}', API\GroupMemberController::class.'@remove');

                // set managers of group
                // @put /api/v2/plus-group/groups/:group/managers/:member
                $api->put('/{group}/managers/{member}', API\GroupMemberController::class.'@setManager');

                // remove managers of group
                // @put /api/v2/plus-group/groups/:group/managers/:member
                $api->delete('/{group}/managers/{member}', API\GroupMemberController::class.'@removeManager');

                // set blacklist of group
                // @put /api/v2/plus-group/groups/:group/blacklist/:member
                $api->put('/{group}/blacklist/{member}', API\GroupMemberController::class.'@setBlacklist');

                // remove blacklist of group
                // @delete /api/v2/plus-group/groups/:group/blacklist/:member
                $api->delete('/{group}/blacklist/{member}', API\GroupMemberController::class.'@removeBlacklist');

                // set group permissions
                // @patch /api/v2/plus-group/groups/:group/permissions
                $api->patch('/{group}/permissions', API\GroupsController::class.'@permissions');

                // 转让圈主
                // @patch /api/v2/plus-group/groups/:group/owner
                $api->patch('/{group}/owner', API\GroupMemberController::class.'@transferOwner');

                // 圈主和管理员审核拒绝加圈
                // @patch /api/v2/plus-group/groups/:group/members/:member/audit
                $api->patch('/{group}/members/{member}/audit', API\GroupMemberController::class.'@audit');
            });

            $api->group(['prefix' => '/currency-groups'], function (RouteRegisterContract $api) {

                // 积分相关新版加入圈子
                // @put /api/v2/plus-group/groups/:group
                $api->put('/{group}', API\GroupsController::class.'@newJoin');

                // 积分相关新版圈主和管理员审核拒绝加圈
                // @patch /api/v2/plus-group/groups/:group/members/:member/currency-audit
                $api->patch('/{group}/members/{member}/audit', API\GroupMemberController::class.'@newAudit');
            });

            // post auth route.
            $api->group(['prefix' => '/group-posts'], function (RouteRegisterContract $api) {

                // create a comment for a group post.
                // @post /api/v2/plus-group/group-posts/:post/comments
                $api->post('/{post}/comments', API\PostCommentController::class.'@store')->middleware('sensitive:body');

                // delete a comment for a group post.
                // @delete /api/v2/plus-group/group-posts/:post/comments/:comment
                $api->delete('/{post}/comments/{comment}', API\PostCommentController::class.'@delete');

                // like a group post.
                // @post /api/v2/plus-group/group-posts/:post/likes
                $api->post('/{post}/likes', API\PostLikeController::class.'@store');

                // cancel like a group post.
                // @delete /api/v2/plus-group/group-posts/:post/likes
                $api->delete('/{post}/likes', API\PostLikeController::class.'@cancel');

                // reward a group post.
                // @post /api/v2/plus-group/group-posts/:post/rewards
                $api->post('/{post}/rewards', API\PostRewardController::class.'@store');

                // 新版帖子打赏
                $api->post('/{post}/new-rewards', API\NewPostRewardController::class.'@store');

                // 收藏帖子.
                // @post /api/v2/plus-group/group-posts/:post/collections
                $api->post('/{post}/collections', API\PostCollectionController::class.'@store');
                // 取消收藏帖子.
                // @delete /api/v2/plus-group/group-posts/:post/uncollect
                $api->delete('/{post}/uncollect', API\PostCollectionController::class.'@destroy');
            });

            // pinned routes.
            $api->group(['prefix' => '/pinned'], function (RouteRegisterContract $api) {

                // 获取帖子申请置顶列表
                // @get /api/v2/plus-group/pinned/posts
                $api->get('/posts', API\PinnedController::class.'@posts');

                // 申请帖子置顶
                // @post /api/v2/plus-group/pinned/posts/:post
                $api->post('/posts/{post}', API\PinnedController::class.'@storePost');

                // 通过帖子置顶
                // @patch /api/v2/plus-group/pinned/posts/:post/accept
                $api->patch('/posts/{post}/accept', API\PinnedController::class.'@acceptPost');

                // 拒绝帖子置顶
                // @patch /api/v2/plus-group/pinned/posts/:post/reject
                $api->patch('/posts/{post}/reject', API\PinnedController::class.'@rejectPost');

                // 获取帖子评论申请置顶列表
                // @get /api/v2/plus-group/pinned/comments
                $api->get('/comments', API\PinnedController::class.'@comments');

                // 申请评论帖子置顶
                // @post /api/v2/plus-group/pinned/comments/:comment
                $api->post('/comments/{comment}', API\PinnedController::class.'@storeComments');

                // 接收帖子评论置顶
                // @patch /api/v2/plus-group/pinned/comments/:comment/accept
                $api->patch('/comments/{comment}/accept', API\PinnedController::class.'@acceptComments');

                // 拒绝帖子评论置顶
                // @patch /api/v2/plus-group/pinned/comments/:comment/reject
                $api->patch('/comments/{comment}/reject', API\PinnedController::class.'@rejectComments');

                // 圈主和管理员置顶帖子
                // @post /api/v2/plus-group/pinned/posts/:post/create
                $api->post('/posts/{post}/create', API\PinnedController::class.'@postPinnedCreate');

                // 圈主和管理员取消帖子置顶
                // @patch /api/v2/plus-group/pinned/posts/:post/create
                $api->patch('/posts/{post}/cancel', API\PinnedController::class.'@postPinnedCancel');
            });

            // pinned routes.
            $api->group(['prefix' => '/currency-pinned'], function (RouteRegisterContract $api) {

                // 申请帖子置顶
                // @post /api/v2/plus-group/pinned/posts/:post
                $api->post('/posts/{post}', API\NewPinnedController::class.'@storePost');

                // 通过帖子置顶
                // @patch /api/v2/plus-group/pinned/posts/:post/accept
                $api->patch('/posts/{post}/accept', API\NewPinnedController::class.'@acceptPost');

                // 拒绝帖子置顶
                // @patch /api/v2/plus-group/pinned/posts/:post/reject
                $api->patch('/posts/{post}/reject', API\NewPinnedController::class.'@rejectPost');

                // 申请评论帖子置顶
                // @post /api/v2/plus-group/pinned/comments/:comment
                $api->post('/comments/{comment}', API\NewPinnedController::class.'@storeComments');

                // 接收帖子评论置顶
                // @patch /api/v2/plus-group/pinned/comments/:comment/accept
                $api->patch('/comments/{comment}/accept', API\NewPinnedController::class.'@acceptComments');

                // 拒绝帖子评论置顶
                // @patch /api/v2/plus-group/pinned/comments/:comment/reject
                $api->patch('/comments/{comment}/reject', API\NewPinnedController::class.'@rejectComments');
            });

            // report routes.
            $api->group(['prefix' => '/reports'], function (RouteRegisterContract $api) {
                $api->get('/', API\GroupReportController::class.'@reports');
                // 帖子举报.
                $api->post('/posts/{post}', API\GroupReportController::class.'@postReport');
                // 评论举报.
                $api->post('/comments/{comment}', API\GroupReportController::class.'@commentReport');
                // 通过举报审核
                $api->patch('/{report}/accept', API\GroupReportController::class.'@accept');
                // 拒绝举报审核
                $api->patch('/{report}/reject', API\GroupReportController::class.'@reject');
            });

            // 用户收藏的帖子.
            // @post /api/v2/plus-group/group-posts/:post/collections
            // user routes.
            $api->get('user-post-collections', API\PostCollectionController::class.'@collections');
            $api->get('user-groups', API\GroupsController::class.'@userGroups');
            $api->get('user-group-posts', API\PostController::class.'@userPosts');
            $api->get('user-group-audit-members', API\GroupMemberController::class.'@auditMembers');
        });
    });
});
