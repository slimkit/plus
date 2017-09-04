<?php

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
    });

    // SMS 相关
    Route::prefix('sms')->group(function () {
        Route::get('/', 'SmsController@show');
        Route::get('/driver/{dirver}', 'SmsController@showOption');
        Route::patch('/driver/alidayu', 'SmsController@updateAlidayuOption');
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
    Route::delete('/users/{user}', 'UserController@deleteUser');
    Route::get('/users/{user}', 'UserController@showUser');
    Route::patch('/users/{user}', 'UserController@update');
    Route::get('/user/setting', 'UserController@showSetting');
    Route::patch('/user/setting', 'UserController@storeSetting');

    // 系统通知
    Route::any('/system/notice', 'SystemController@pushSystemNotice');

    // 认证类型管理
    Route::get('certification/categories', 'CertificationCategoryController@certifications');
    Route::get('certification/categories/{name}', 'CertificationCategoryController@show');
    Route::put('certification/categories/{name}', 'CertificationCategoryController@update');
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

    // 过滤配置
    Route::prefix('filter-word-categories')->group(function () {
        Route::get('', 'FilterWordCategoryController@index');
        Route::get('/{category}', 'FilterWordCategoryController@show');
        Route::post('', 'FilterWordCategoryController@store');
        Route::patch('/{category}', 'FilterWordCategoryController@update');
        Route::delete('/{category}', 'FilterWordCategoryController@delete');
    });

    // 过滤类型
    Route::prefix('filter-word-types')->group(function () {
        Route::get('', 'FilterWordTypeController@index');
        Route::patch('/{id}/status', 'FilterWordTypeController@status');
    });

    // 敏感词
    Route::prefix('sensitive-words')->group(function () {
        Route::get('', 'SensitiveWordController@index');
        Route::post('', 'SensitiveWordController@store');
        Route::get('/{word}', 'SensitiveWordController@show');
        Route::patch('/{word}', 'SensitiveWordController@update');
        Route::delete('/{word}', 'SensitiveWordController@delete');
    });
});
