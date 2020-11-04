<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2016-Present ZhiYiChuangXiang Technology Co., Ltd.     |
 * +----------------------------------------------------------------------+
 * | This source file is subject to enterprise private license, that is   |
 * | bundled with this package in the file LICENSE, and is available      |
 * | through the world-wide-web at the following url:                     |
 * | https://github.com/slimkit/plus/blob/master/LICENSE                  |
 * +----------------------------------------------------------------------+
 * | Author: Slim Kit Group <master@zhiyicx.com>                          |
 * | Homepage: www.thinksns.com                                           |
 * +----------------------------------------------------------------------+
 */

use Illuminate\Contracts\Routing\Registrar as RouteContract;
use Illuminate\Support\Facades\Route;
use Zhiyi\Plus\Admin\Controllers as AdminControllers;

Route::middleware(['auth:web', 'admin'])->prefix('admin')->group(function (RouteContract $route) {
    $route->get('im/helper-user', AdminControllers\ImHelperUserController::class.'@fetch');
    $route->put('im/helper-user', AdminControllers\ImHelperUserController::class.'@update');
    $route->get('trashed-users', AdminControllers\UserTrashedController::class.'@index');
    $route->delete('trashed-users/{user}', AdminControllers\UserTrashedController::class.'@restore');
    $route->get('about-us', AdminControllers\AboutUsController::class.'@show');
    $route->patch('about-us', AdminControllers\AboutUsController::class.'@store');

    $route->get('file-storage/image-dimension', AdminControllers\FileStorage\ImageDimension::class.'@show');
    $route->patch('file-storage/image-dimension', AdminControllers\FileStorage\ImageDimension::class.'@update');

    $route->get('file-storage/file-mime-types', AdminControllers\FileStorage\MimeType::class.'@show');
    $route->patch('file-storage/file-mime-types', AdminControllers\FileStorage\MimeType::class.'@update');

    $route->get('file-storage/file-size', AdminControllers\FileStorage\FileSize::class.'@show');
    $route->patch('file-storage/file-size', AdminControllers\FileStorage\FileSize::class.'@update');

    $route->get('file-storage/default-filesystem', AdminControllers\FileStorage\DefaultFilesystem::class.'@show');
    $route->patch('file-storage/default-filesystem', AdminControllers\FileStorage\DefaultFilesystem::class.'@update');

    $route->get('file-storage/filesystems/local', AdminControllers\FileStorage\LocalFilesystem::class.'@show');
    $route->patch('file-storage/filesystems/local', AdminControllers\FileStorage\LocalFilesystem::class.'@update');

    $route->get('file-storage/filesystems/aliyun-oss', AdminControllers\FileStorage\AliyunOSSFilesystem::class.'@show');
    $route->patch('file-storage/filesystems/aliyun-oss', AdminControllers\FileStorage\AliyunOSSFilesystem::class.'@update');

    $route->get('file-storage/channels/public', AdminControllers\FileStorage\PublicChannel::class.'@show');
    $route->patch('file-storage/channels/public', AdminControllers\FileStorage\PublicChannel::class.'@update');

    $route->get('setting/security/pay-validate-password', AdminControllers\Setting\Security::class.'@payValidateSwitch');
    $route->put('setting/security/pay-validate-password', AdminControllers\Setting\Security::class.'@changePayValidateSwitch');

    // 环信配置
    $route->get('setting/vendor/easemob', AdminControllers\Setting\Easemob::class.'@getConfigure');
    $route->put('setting/vendor/easemob', AdminControllers\Setting\Easemob::class.'@setConfigure');

    // QQ 配置
    $route->get('setting/vendor/qq', AdminControllers\Setting\QQ::class.'@getConfigure');
    $route->put('setting/vendor/qq', AdminControllers\Setting\QQ::class.'@setConfigure');

    // 微信配置
    $route->get('setting/vendor/wechat', AdminControllers\Setting\WeChat::class.'@getConfigure');
    $route->put('setting/vendor/wechat', AdminControllers\Setting\WeChat::class.'@setConfigure');

    // 微博配置
    $route->get('setting/vendor/weibo', AdminControllers\Setting\Weibo::class.'@getConfigure');
    $route->put('setting/vendor/weibo', AdminControllers\Setting\Weibo::class.'@setConfigure');

    // 微信公众平台
    $route->get('setting/vendor/wechat-mp', AdminControllers\Setting\WeChatMp::class.'@getConfigure');
    $route->put('setting/vendor/wechat-mp', AdminControllers\Setting\WeChatMp::class.'@setConfigure');
});
