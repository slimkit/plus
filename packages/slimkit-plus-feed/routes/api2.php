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

Route::prefix('/feeds')->group(function () {
    // 动态
    Route::get('/', 'FeedController@index');
    Route::get('/{feed}', 'FeedController@show')->where(['feed' => '[0-9]+']);

    // 评论
    Route::get('/{feed}/comments', 'FeedCommentController@index');
    Route::get('/{feed}/comments/{comment}', 'FeedCommentController@show');

    // 排行
    Route::get('/ranks', 'RankController@index');

    // 获取打赏列表
    Route::get('/{feed}/rewards', 'RewardController@index');

    // 获取点赞列表
    Route::get('/{feed}/likes', 'LikeController@index');

    /*
     * 需要授权的路由
     */
    Route::middleware('auth:api')->group(function () {

        // 动态
        Route::post('/', 'FeedController@store')->middleware('sensitive:feed_content');
        Route::delete('/{feed}', 'FeedController@destroy');
        Route::patch('/{feed}/comment-paid', 'FeedPayController@commentPaid');

        Route::delete('/{feed}/currency', 'FeedController@newDestroy');

        // 评论
        Route::post('/{feed}/comments', 'FeedCommentController@store')->middleware('sensitive:body');
        Route::delete('/{feed}/comments/{comment}', 'FeedCommentController@destroy');

        // 收藏
        Route::get('/collections', 'FeedCollectionController@list');
        Route::post('/{feed}/collections', 'FeedCollectionController@store');
        Route::delete('/{feed}/uncollect', 'FeedCollectionController@destroy');

        // 固定
        Route::post('/{feed}/pinneds', 'PinnedController@feedPinned');
        Route::post('/{feed}/comments/{comment}/pinneds', 'PinnedController@commentPinned');
        Route::patch('/{feed}/comments/{comment}/pinneds/{pinned}', 'CommentPinnedController@pass');
        Route::delete('/{feed}/comments/{comment}/unpinned', 'CommentPinnedController@delete');

        // 使用积分进行的置顶流程
        Route::post('/{feed}/currency-pinneds', 'NewPinnedController@feedPinned');
        Route::post('/{feed}/comments/{comment}/currency-pinneds', 'NewPinnedController@commentPinned');
        Route::patch('/{feed}/comments/{comment}/currency-pinneds/{pinned}', 'NewCommentPinnedController@pass');

        // 喜欢
        Route::post('/{feed}/like', 'LikeController@store');
        Route::delete('/{feed}/unlike', 'LikeController@destroy');

        // 打赏
        Route::post('/{feed}/rewards', 'RewardController@reward');

        // 新版动态打赏
        Route::post('/{feed}/new-rewards', 'NewRewardController@reward');

        // 举报
        Route::post('/{feed}/reports', 'ReportController@feed');

        // 获取平均置顶积分
        Route::get('/average', 'AverageController@show');
    });
});

/*
 * 需要授权的非 feeds 资源路由.
 */
Route::middleware('auth:api')->group(function () {

    // 评论固定审核
    Route::get('/user/feed-comment-pinneds', 'CommentPinnedController@index');
    Route::delete('/user/feed-comment-pinneds/{pinned}', 'CommentPinnedController@reject');
    Route::delete('/user/feed-comment-currency-pinneds/{pinned}', 'NewCommentPinnedController@reject');
});
