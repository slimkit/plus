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

Route::middleware('auth')->group(function () {
    // 歌曲相关
    Route::get('/', 'HomeController@show')->name('music:list');
    Route::delete('/{music}', 'HomeController@handleDelete')->name('music:delete');
    Route::get('/add', 'HomeController@handleAddMuisc')->name('music:add');
    Route::post('/add', 'HomeController@handleStoreMuisc')->name('music:store');

    // 附件上传
    Route::post('/storage', 'MusicStorageController@store')->name('music:storage');
    Route::post('/specials/storage', 'MusicStorageController@specialStorage')->name('special:storage');

    // 专辑相关
    Route::get('/specials', 'HomeController@showSpecial')->name('music:special');
    Route::get('/specials/{special}', 'HomeController@specialDetail')->name('special:detail')->where(['special' => '[0-9]+']);
    Route::patch('/specials/{special}', 'HomeController@handleUpdateSpecial')->name('special:update');
    Route::delete('/specials/{special}', 'HomeController@handleDisableSpecial')->name('special:delete');
    Route::get('/specials/add', 'HomeController@handleAddSpecial')->name('special:add');
    Route::post('/special', 'HomeController@handleStoreSpecial')->name('special:store');

    // 歌手相关
    Route::prefix('/singers')->group(function () {
        Route::get('/', 'SingerController@list')->name('music:singers');
        Route::delete('/{singer}', 'SingerController@disabled')->name('music:singers:disabled');
        Route::get('/add', 'SingerController@add')->name('music:singers:add');
        Route::post('/store', 'SingerController@store')->name('music:singers:store');
        Route::get('/{singer}', 'SingerController@detail')->name('music:singers:detail');
        Route::patch('/{singer}', 'SingerController@update')->name('music:singers:update');
        Route::patch('/{singer}/restore', 'SingerController@restore')->name('music:singers:restore');
    });

    Route::prefix('/comments')->group(function () {
        Route::get('/musics', 'CommentController@lists')->name('music:all:comments');

        Route::get('/musics/{music}', 'CommentController@musicComments')->name('music:comments');
        Route::get('/specials/{special}', 'CommentController@specialComments')->name('special:comments');
        Route::delete('/musics/{music}/comments/{comment}', 'CommentController@delete')->name('music:comments:delete');
        Route::delete('/specials/{special}/comments/{comment}', 'CommentController@specialDelete')->name('special:comments:delete');
    });

    Route::post('/storage', 'MusicStorageController@store')->name('music:storage');
});
