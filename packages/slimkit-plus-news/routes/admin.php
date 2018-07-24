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

Route::get('/', 'HomeController@show')->name('news:admin');

Route::prefix('news')->group(function () {
    Route::get('/', 'NewsController@getNewsList');
    Route::get('/recycle', 'NewsController@getRecycleList');
    Route::get('/info/{news_id}', 'NewsController@getNews')->where(['news_id'=>'[0-9]+']);
    Route::get('/cates', 'NewsCateController@getCateList');
    Route::post('/audit/{news_id}', 'NewsController@auditNews')->where(['news_id'=>'[0-9]+']);
    Route::patch('/{news}/recommend', 'NewsController@recommend')->where(['news'=>'[0-9]+']);
    Route::post('/handle_news', 'NewsController@doSaveNews');
    Route::post('/handle_cate', 'NewsCateController@doNewsCate');
    Route::post('/handle_rec', 'NewsRecommendController@doRecommendInfo');
    Route::delete('/del/{news_id}/news', 'NewsController@delNews')->where(['news_id'=>'[0-9]+']);
    Route::delete('/del/{cate_id}/cate', 'NewsCateController@delCate')->where(['cate_id'=>'[0-9]+']);
    Route::delete('/del/{rid}/recommend', 'NewsRecommendController@delNewsRecommend')->where(['cate_id'=>'[0-9]+']);
    Route::get('/recommend', 'NewsRecommendController@getRecommendList');
    Route::get('/rec/{rid}', 'NewsRecommendController@getNewsRecommend')->where(['rid'=>'[0-9]+']);

    // 投稿设置
    Route::get('/config', 'NewsConfigController@show');

    // 更新投稿设置
    Route::patch('/config/contribute', 'NewsConfigController@setContribute');

    // 更新投稿金额设置
    Route::patch('/config/pay_contribute', 'NewsConfigController@setPayContribute');

    // 获取审核置顶列表
    Route::get('/pinneds', 'NewsPinnedController@index');

    // 审核置顶
    Route::patch('/pinned/{pinned}', 'NewsPinnedController@audit');

    // 后台设置置顶
    Route::post('/{news}/pinned', 'NewsPinnedController@set');

    // 后台取消设置置顶
    Route::delete('/{news}/pinned', 'NewsPinnedController@cancel');

    // 后台删除资讯申请相关
    Route::get('/applylogs', 'NewsApplyLogController@index');
    Route::put('/applylogs/{log}/accept', 'NewsApplyLogController@accept');
    Route::put('/applylogs/{log}/reject', 'NewsApplyLogController@reject');
});
