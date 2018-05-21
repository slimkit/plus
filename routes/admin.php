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
use Illuminate\Contracts\Routing\Registrar as RouteRegisterContract;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group([
    'middleware' => [
        'auth:web', 'admin',
    ],
], function (RouteRegisterContract $route) {

    // Admin Index.
    // @GET /admin
    $route->get('/', 'HomeController@index');

    // 后台导航
    // @GET /admin/manages
    $route->get('/manages', 'HomeController@showManages');

    // Role
    // @Route /roles
    $route->group(['prefix' => 'roles'], function (RouteRegisterContract $route) {

        // Get all role.
        // @GET /roles
        $route->get('/', 'RoleController@roles');

        // Create role.
        // @POST /roles
        $route->post('/', 'RoleController@createRole');

        // Update a role.
        // @PATCH /roles/:role
        $route->patch('/{role}', 'RoleController@updateRole');

        // Delete a role.
        // @DELETE /roles/:role
        $route->delete('/{role}', 'RoleController@delete');

        // Get a role info.
        // @get /roles/:role
        $route->get('/{role}', 'RoleController@showRole');
    });

    // Abilities.
    // @Route /abilities
    $route->group(['prefix' => 'abilities'], function (RouteRegisterContract $route) {

        // Get all abilities.
        // @get /abilities
        $route->get('/', 'RoleController@abilities');

        // Create a ability.
        // @post /abilities
        $route->post('/', 'RoleController@createAbility');

        // Update a ability.
        // @patch /abilities/:ability
        $route->patch('/{ability}', 'RoleController@updateAbility');

        // Delete a ability.
        // @delete /abilities/:ability
        $route->delete('/{ability}', 'RoleController@deleteAbility');
    });

    // CDN
    // @Route /admin/cdn
    $route->group(['prefix' => 'cdn'], function (RouteRegisterContract $route) {

        // Get cdn selected
        $route->get('/selected', 'CdnController@getCdnSelected');

        // Local.
        $route->get('/filesystem/disk', 'CdnController@getFilesystemDisk');
        $route->post('/filesystems/public', 'CdnController@setPublicDisk');
        $route->get('/filesystems/local', 'CdnController@getLocalDisk');
        $route->post('/filesystems/local', 'CdnController@setLocalDisk');
        $route->get('/filesystems/s3', 'CdnController@getS3Disk');
        $route->post('/filesystems/s3', 'CdnController@setS3Disk');

        // qiniu Config.
        $route->get('/qiniu', 'CdnController@qiniu');
        $route->post('/qiniu', 'CdnController@setQiniu');

        // alioss Config.
        $route->get('/alioss', 'CdnController@alioss');
        $route->post('/alioss', 'CdnController@setAlioss');
    });

    // 附件部分
    // @Route /admin/files
    $route->group(['prefix' => 'files'], function (RouteRegisterContract $route) {

        // 附件配置部分
        $route->get('/setting', 'FileController@getConfig');
        $route->patch('/setting', 'FileController@setConfig');
    });

    /* 敏感词路由 */
    // @Route /admin/sensitives
    $route->group(['prefix' => 'sensitives'], function (RouteRegisterContract $route) {

        /*
         * 获取敏感词列表
         *
         * @get /admin/sensitves
         */
        $route->get('/', 'SensitiveController@index');

        /*
         * 创建敏感词。
         *
         * @post /admin/sensitives
         */
        $route->post('/', 'SensitiveController@store');

        /*
         * Update a sensitive.
         *
         * @put /admin/sensitives/:sensitive
         */
        $route->patch('/{sensitive}', 'SensitiveController@update');

        /*
         * destroy a sensitive.
         *
         * @delete /admin/sensitives/:sensitive
         */
        $route->delete('/{sensitive}', 'SensitiveController@destroy');
    });

    // web clients
    $route->get('settings/web-clients', 'WebClientsController@fetch');
    $route->patch('settings/web-clients', 'WebClientsController@update');

    // CORS
    $route->get('settings/cors', 'CorsController@fetch');
    $route->put('settings/cors', 'CorsController@update');
});

Route::middleware('auth:web')
->middleware('admin')
->group(function () {

    // 钱包
    Route::prefix('wallet')->group(function () {
        // 充值选项
        Route::get('/labels', 'WalletLabelController@labels');
        Route::post('/labels', 'WalletLabelController@storeLabel');
        Route::delete('/labels/{label}', 'WalletLabelController@deleteLabel');

        // 转换比例
        Route::get('/ratio', 'WalletRatioController@show');
        Route::patch('/ratio', 'WalletRatioController@update');

        // 规则描述
        Route::get('/rule', 'WalletRuleController@show');
        Route::patch('/rule', 'WalletRuleController@update');

        // 提现规则
        Route::get('/cash', 'WalletCashSettingController@show');
        Route::patch('/cash', 'WalletCashSettingController@update');

        // 提现记录
        Route::get('/cashes', 'WalletCashController@show');
        Route::patch('/cashes/{cash}/passed', 'WalletCashController@passed');
        Route::patch('/cashes/{cash}/refuse', 'WalletCashController@refuse');

        //  Ping++
        Route::get('/pingpp', 'WalletPingPlusPlusController@show');
        Route::patch('/pingpp', 'WalletPingPlusPlusController@update');

        // 支付选项
        Route::get('/recharge/types', 'WalletRechargeTypeController@show');
        Route::patch('/recharge/types', 'WalletRechargeTypeController@update');

        // 凭据列表
        Route::get('/charges', 'WalletChargeController@show');

        // 钱包统计
        Route::get('/statistics', 'WalletStatisticsController@index');

        // 钱包开关
        Route::get('/switch', 'WalletSwitchController@show');
        Route::patch('/switch', 'WalletSwitchController@update');

        // 原生支付配置设置
        Route::get('/newPaySetting', 'NewPaySettingController@index');
        Route::post('/newPaySetting', 'NewPaySettingController@store');
    });

    // SMS 相关
    Route::prefix('sms')->group(function () {
        Route::get('/', 'SmsController@show');
        Route::get('/driver/{dirver}', 'SmsController@showOption');
        Route::get('/templates', 'SmsController@smsTemplate');
        Route::get('/gateways', 'SmsController@showGateway');

        Route::patch('/driver/alidayu', 'SmsController@updateAlidayuOption');
        Route::patch('/driver/aliyun', 'SmsController@updateAliyunOption');
        Route::patch('/driver/yunpian', 'SmsController@updateYunpianOption');
        Route::patch('update/gateways', 'SmsController@updateGateway');
        Route::patch('update/templates', 'SmsController@updateTemplate');
    });

    /* -----------  Mail  ------------ */
    Route::get('/site/mail', 'SiteController@mail');
    Route::post('/site/sendmail', 'SiteController@sendMail');
    Route::patch('/site/mail', 'SiteController@updateMailInfo');

    /* ------------------------------ */
    Route::get('/site/baseinfo', 'SiteController@get');
    Route::patch('/site/baseinfo', 'SiteController@updateSiteInfo');

    /* ------------- system info ------------*/
    Route::get('/site/systeminfo', 'SiteController@server');

    /* ------------- tags -----------------*/

    Route::prefix('site/tags')->group(function () {
        // 标签列表(带分页)
        Route::get('/', 'TagController@lists');
        Route::get('/{tag}', 'TagController@tag')
            ->where('tag', '[0-9]+');

        // 分类列表(带分页)
        Route::get('/tag_categories', 'TagController@categories');

        // 标签分类(不带分页)
        Route::get('/categories', 'TagController@cateForTag');

        // 添加标签
        Route::post('/', 'TagController@store');

        Route::post('/tag_categories', 'TagController@storeCate');

        Route::patch('/tag_categories/{cate}', 'TagController@updateCate');

        Route::patch('/{tag}', 'TagController@update')
            ->where('tag', '[0-9]+');

        Route::delete('/{tag}', 'TagController@delete');

        Route::delete('/tag_categories/{cate}', 'TagController@deleteCategory');
    });

    // 后台表单
    Route::get('/forms', 'SiteController@showForms');

    // area
    Route::get('/site/areas', 'SiteController@areas');
    Route::get('/site/areas/hots', 'SiteController@hots');
    Route::post('/site/areas', 'SiteController@doAddArea');
    Route::post('/site/areas/hots', 'SiteController@doHots');
    Route::delete('/site/areas/{id}', 'SiteController@deleteArea');
    Route::patch('/site/areas/{area}', 'SiteController@patchArea');

    // users
    Route::get('/users', 'UserController@users');
    Route::post('/users', 'UserController@store');

    // 推荐用户相关
    Route::post('/users/recommends', 'UserController@handleRecommend');
    Route::delete('/users/recommends/{user}', 'UserController@handleUnRecommend');
    Route::get('/users/recommends', 'UserController@recommends');
    Route::post('/users/famous', 'UserController@handleFamous');
    Route::delete('/users/famous/{user}', 'UserController@handleUnFamous');

    // 注册配置相关
    Route::get('/users/register-setting', 'UserController@getRegisterSetting');
    Route::post('/users/register-setting', 'UserController@updateRegisterSetting');

    Route::delete('/users/{user}', 'UserController@deleteUser');
    Route::get('/users/{user}', 'UserController@showUser');
    Route::patch('/users/{user}', 'UserController@update');
    Route::get('/user/setting', 'UserController@showSetting');
    Route::patch('/user/setting', 'UserController@storeSetting');

    // 系统通知
    Route::any('/system/notice', 'SystemController@pushSystemNotice');

    // 认证类型管理
    Route::get('certification/categories', 'CertificationCategoryController@certifications');
    Route::get('certification/categories/{category}', 'CertificationCategoryController@show');
    Route::put('certification/categories/{category}', 'CertificationCategoryController@update');
    Route::post('certification/categories/{category}/icon/upload', 'CertificationCategoryController@iconUpload');
    // 认证管理
    Route::get('certifications', 'CertificationController@index');
    Route::get('certifications/{certification}', 'CertificationController@show');
    Route::patch('certifications/{certification}', 'CertificationController@update');
    Route::post('certifications', 'CertificationController@store');
    Route::patch('certifications/{certification}/pass', 'CertificationController@passCertification');
    Route::patch('certifications/{certification}/reject', 'CertificationController@rejectCertification');
    Route::get('find/nocertification/users', 'CertificationController@findNoCertificationUsers');

    // 会话管理
    Route::get('conversations', 'ConversationController@index');
    Route::delete('conversations/{conversation}', 'ConversationController@delete');

    /* ------------- 广告管理 -----------------*/
    Route::get('ads', 'AdvertisingController@ads');
    Route::get('ads/spaces', 'AdvertisingController@spaces');
    Route::get('ads/{ad}', 'AdvertisingController@showAd');
    Route::post('ads', 'AdvertisingController@storeAd');
    Route::delete('ads/{ad}', 'AdvertisingController@deleteAd');
    Route::put('ads/{ad}', 'AdvertisingController@updateAd');

    /* ------------- 站点管理 -----------------*/
    Route::get('site/configures', 'SiteController@siteConfigurations');
    Route::put('update/site/configure', 'SiteController@updateSiteConfigure');

    /*-------------- 后台配置-----------------*/

    Route::get('/site/background', 'SiteController@getBackGroundConfiguration');
    Route::patch('/site/background', 'SiteController@setBackGroundConfiguration');

    /* ------------- 金币管理 -----------------*/

    // 金币类型管理
    Route::get('gold/types', 'GoldTypeController@types');
    Route::post('gold/types', 'GoldTypeController@storeType');
    Route::patch('gold/types/{type}/open', 'GoldTypeController@openType');
    Route::delete('gold/types/{type}', 'GoldTypeController@deleteType');

    // 金币规则管理
    Route::get('gold/rules', 'GoldRuleController@rules');
    Route::get('gold/rules/abilities', 'GoldRuleController@abilities');
    Route::post('gold/rules', 'GoldRuleController@storeRule');
    Route::put('gold/rules/{rule}', 'GoldRuleController@updateRule');
    Route::get('gold/rules/{rule}', 'GoldRuleController@showRule');
    Route::delete('gold/rules/{rule}', 'GoldRuleController@deleteRule');

    /* ------------- 打赏管理 -----------------*/
    // 清单
    Route::get('rewards', 'RewardController@rewards');
    // 统计
    Route::get('rewards/statistics', 'RewardController@statistics');

    Route::get('rewards/export', 'RewardController@export');

    /* ------------- 举报管理 -----------------*/
    Route::get('reports', 'ReportController@index');
    Route::patch('reports/{report}/deal', 'ReportController@deal');
    Route::patch('reports/{report}/reject', 'ReportController@reject');

    /*-------------- 辅助功能 -----------------*/
    Route::prefix('/auxiliary')->group(function () {
        Route::get('/clear', 'AuxiliaryController@cleanCache');
    });

    /* ------------- 积分设置 -----------------*/
    Route::prefix('/currency')->group(function () {
        Route::get('/', 'CurrencyController@index');
        Route::post('/add', 'CurrencyController@add');
        Route::get('/config', 'CurrencyController@showConfig');
        Route::patch('/config', 'CurrencyController@updateConfig');

        Route::get('/list', 'CurrencyController@list');
        Route::get('/overview', 'CurrencyController@overview');

        Route::prefix('/cash')->group(function () {
            Route::get('', 'CurrencyCashController@list');
            Route::patch('/{order}/audit', 'CurrencyCashController@audit');
        });

        Route::prefix('/apple')->group(function () {
            Route::get('/config', 'CurrencyAppleController@getConfig');
            Route::patch('/config', 'CurrencyAppleController@setConfig');

            Route::get('/products', 'CurrencyAppleController@getProducts');
            Route::post('/products', 'CurrencyAppleController@addProduct');
            Route::delete('/products', 'CurrencyAppleController@delProduct');
        });
    });

    Route::prefix('new-wallet')->group(function () {
        Route::get('/statistics', 'NewWalletController@statistics');
        Route::get('/waters', 'NewWalletController@waters');
    });
});
