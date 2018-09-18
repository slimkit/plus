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

Route::get('/music/specials', 'MusicSpecialController@list');

// 专辑详情
Route::get('/music/specials/{special}', 'MusicSpecialController@show');

// 音乐详情
Route::get('/music/{music}', 'MusicController@show')->where(['music' => '[0-9]+']);

// 需认证接口
Route::middleware('auth:api')->group(function () {

    // 用户收藏列表
    Route::get('/music/collections', 'MusicCollectionController@list');

    // 添加音乐评论
    Route::post('/music/{music}/comments', 'MusicCommentController@store')->middleware('sensitive:body');

    // 获取音乐评论
    Route::get('/music/{music}/comments', 'MusicCommentController@list');

    // 增加音乐分享数
    Route::patch('/music/{music}/share', 'MusicController@share');

    // 添加专辑评论
    Route::post('/music/specials/{special}/comments', 'MusicCommentController@specialStore')->middleware('sensitive:body');

    // 获取专辑评论
    Route::get('/music/specials/{special}/comments', 'MusicCommentController@specialList');

    // 删除音乐评论
    Route::delete('/music/{music}/comments/{comment}', 'MusicCommentController@delete');

    // 删除专辑评论
    Route::delete('/music/specials/{special}/comments/{comment}', 'MusicCommentController@specialDelete');

    // 点赞音乐
    Route::post('/music/{music}/like', 'MusicLikeController@like');

    // 取消点赞音乐
    Route::delete('/music/{music}/like', 'MusicLikeController@cancel');

    // 收藏专辑
    Route::post('/music/specials/{special}/collection', 'MusicCollectionController@store');

    // 取消收藏
    Route::delete('/music/specials/{special}/collection', 'MusicCollectionController@delete');

    // 增加专辑分享数
    Route::patch('/music/specials/{special}/share', 'MusicSpecialController@share');

    // 获取已购买的音乐
    Route::get('/music/paids', 'MusicPaidController@musics');

    // 获取已购买的专辑
    Route::get('/music-specials/paids', 'MusicPaidController@specials');
});
