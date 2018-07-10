<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2018 Chengdu ZhiYiChuangXiang Technology Co., Ltd.     |
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
use Illuminate\Contracts\Routing\Registrar as RouteContract;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\API2\Controllers as API2;

/*
|--------------------------------------------------------------------------
| RESTful API version 2.
|--------------------------------------------------------------------------
|
| Define the version of the interface that conforms to most of the
| REST ful specification.
|
*/
Route::group(['prefix' => 'v2'], function (RouteContract $api) {

    /*
    |-----------------------------------------------------------------------
    | No user authentication required.
    |-----------------------------------------------------------------------
    |
    | Here are some public routes, public routes do not require user
    | authentication, and if it is an optional authentication route to
    | obtain the current authentication user, use `$request-> user ('api')`.
    |
    */

    $api->group(['prefix' => '/news'], function (RouteContract $api) {

        // 获取资讯列表
        $api->get('/', API2\NewsController::class.'@index');

        // 获取资讯详情
        $api->get('/{news}', API2\NewsController::class.'@detail')->where(['news' => '[0-9]+']);

        // 获取一条资讯的评论列表
        $api->get('/{news}/comments', API2\CommentController::class.'@index');

        // 获取一条资讯的打赏列表
        $api->get('/{news}/rewards', API2\RewardController::class.'@index');

        // 获取一条资讯的打赏统计
        $api->get('/{news}/rewards/sum', API2\RewardController::class.'@sum');

        // 获取专辑分类列表
        $api->get('/cates', API2\CateController::class.'@list');

        // 获取一个分类的置顶资讯列表
        $api->get('/categories/pinneds', API2\NewsController::class.'@pinned');

        // 获取资讯点赞列表
        $api->get('/{news}/likes', API2\LikeController::class.'@index');

        // 获取一条资讯的相关资讯
        $api->get('/{news}/correlations', API2\NewsController::class.'@correlation');

        // 获取资讯排行
        $api->get('/ranks', API2\RankController::class.'@index');
    });

    /*
    |-----------------------------------------------------------------------
    | Define a route that requires user authentication.
    |-----------------------------------------------------------------------
    |
    | The routes defined here are routes that require the user to
    | authenticate to access.
    |
    */

    $api->group(['middleware' => 'auth:api'], function (RouteContract $api) {

        // News contributes.
        $api->group(['prefix' => '/news'], function (RouteContract $api) {
            // 获取平均置顶积分
            $api->get('/average', API2\AverageController::class.'@show');
            // 关注资讯分类
            $api->patch('/categories/follows', API2\CateController::class.'@follow');

            // Send news contributes.
            $api->post('/categories/{category}/news', API2\ContributeController::class.'@store')->middleware('sensitive:title,content,subject,from,author');

            // Update news contributes.
            $api->patch('/categories/{category}/news/{news}', API2\ContributeController::class.'@update')->middleware('sensitive:title,content,subject,from,author');

            // Send news contributes by currency.
            $api->post('/categories/{category}/currency-news', API2\ContributeController::class.'@newStore')->middleware('sensitive:title,content,subject,from,author');

            // Delete new contributes.
            $api->delete('/categories/{category}/news/{news}', API2\ContributeController::class.'@destroy');

            // 申请投稿退款
            $api->put('/categories/{category}/news/{news}', API2\ContributeController::class.'@revoked');

            // Reward news.
            $api->post('/{news}/rewards', API2\RewardController::class.'@reward');

            // 新版打赏.
            $api->post('/{news}/new-rewards', API2\NewRewardController::class.'@reward');

            // 点赞资讯
            $api->post('/{news}/likes', API2\LikeController::class.'@like');

            // 取消点赞资讯
            $api->delete('/{news}/likes', API2\LikeController::class.'@cancel');

            // 举报一条资讯
            $api->post('/{news}/reports', API2\ReportController::class.'@news');

            // top comments
            // 申请评论置顶
            $api->post('/{news}/comments/{comment}/pinneds', API2\PinnedController::class.'@commentPinned');

            // 通过积分申请评论置顶
            $api->post('/{news}/comments/{comment}/currency-pinneds', API2\NewPinnedController::class.'@commentPinned');

            // 查看评论置顶
            $api->get('/comments/pinneds', API2\CommentPinnedController::class.'@index');

            // 审核评论置顶
            $api->patch('/{news}/comments/{comment}/pinneds/{pinned}', API2\CommentPinnedController::class.'@accept');

            // 拒绝评论置顶
            $api->patch('/{news}/comments/{comment}/pinneds/{pinned}/reject', API2\CommentPinnedController::class.'@reject');

            // 审核评论置顶
            $api->patch('/{news}/comments/{comment}/currency-pinneds/{pinned}', API2\NewCommentPinnedController::class.'@accept');

            // 拒绝评论置顶
            $api->patch('/{news}/comments/{comment}/currency-pinneds/{pinned}/reject', API2\NewCommentPinnedController::class.'@reject');

            // 取消评论置顶
            $api->delete('/{news}/comments/{comment}/pinneds/{pinned}', API2\CommentPinnedController::class.'@destroy');

            // 申请资讯置顶
            $api->post('/{news}/pinneds', API2\PinnedController::class.'@newsPinned');

            // 通过积分申请资讯置顶
            $api->post('/{news}/currency-pinneds', API2\NewPinnedController::class.'@newsPinned');

            // 评论一条资讯
            $api->post('/{news}/comments', API2\CommentController::class.'@store')->middleware('sensitive:body');

            // 删除一条资讯的指定评论
            $api->delete('/{news}/comments/{comment}', API2\CommentController::class.'@destroy');
            $api->get('/pinneds', API2\CommentPinnedController::class.'@newsList');

            // 获取收藏资讯
            $api->get('/collections', API2\CollectionController::class.'@index');

            // 收藏资讯
            $api->post('/{news}/collections', API2\CollectionController::class.'@collection');

            // 取消收藏资讯
            $api->delete('/{news}/collections', API2\CollectionController::class.'@cancel');
        });
        // Users API.
        $api->group(['prefix' => 'user'], function (RouteContract $api) {

            // 用户资讯列表.
            $api->get('/news/contributes', API2\ContributeController::class.'@index');
        });
    });
});
