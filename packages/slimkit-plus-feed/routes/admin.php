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

Route::get('/', 'HomeController@show')->name('feed:admin');
Route::get('/statistics', 'HomeController@statistics');
Route::get('/feeds', 'FeedController@index');
Route::delete('/feeds/{feed}', 'FeedController@destroy')
    ->where('feed', '[0-9]+');
Route::patch('/feeds/{feed}/review', 'FeedController@reviewFeed');
Route::get('/comments', 'CommentController@show');
Route::delete('/comments/{comment}', 'CommentController@delete');
Route::delete('/feeds/{feed}/pinned', 'FeedPinnedController@destroy');

Route::patch('/comments/{comment}/pinneds/{pinned}', 'CommentController@accept');

Route::post('/comments/{comment}/pinned', 'CommentController@set');

// 拒绝动态置顶申请
Route::delete('/feeds/pinneds/{pinned}', 'FeedPinnedController@reject');

// 拒绝评论置顶
Route::delete('/comments/pinneds/{pinned}', 'CommentController@reject');

Route::get('/feeds/{feed}', 'FeedController@show');

// 获取动态收费配置
Route::get('/paycontrol', 'PayControlController@getCurrentStatus');

// 更新动态收费配置
Route::patch('/paycontrol', 'PayControlController@updateStatus');

// 被软删除的动态
Route::get('/deleted-feeds', 'FeedController@deleted');
// Route::get('/deleted-comments', 'CommentController@deleted');

// 恢复
Route::patch('/feeds', 'FeedController@restore');

// 真删除
Route::delete('/feeds', 'FeedController@delete');

// File
Route::get('/files/{file}', 'FileController@show');

Route::patch('/status/open', 'HomeController@handleComponentStatus');

Route::patch('/status/reward', 'HomeController@handleRewardStatus');

// 获取审核置顶列表
Route::get('/pinneds', 'FeedPinnedController@index');

// 审核置顶
Route::patch('/pinned/{pinned}', 'FeedPinnedController@audit');

// 后台设置置顶
Route::post('/{feed}/pinned', 'FeedPinnedController@set');
