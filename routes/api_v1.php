<?php

use Zhiyi\Plus\Http\Middleware\V1 as Middleware;

// 获取手机验证码
Route::post('/auth/phone/send-code', 'AuthController@sendPhoneCode')
    ->middleware(Middleware\VerifyPhoneNumber::class) // 验证手机号格式是否正确
    ->middleware(Middleware\VerifySendPhoneCodeType::class) // 验证发送验证码类型等集合
;

// 用户登录
Route::post('/auth', 'AuthController@login')
    ->middleware(Middleware\CheckDeviceCodeExisted::class) // 验证设备号是否存在
    ->middleware(Middleware\VerifyPhoneNumber::class) // 验证手机号码是否正确
    ->middleware(Middleware\CheckUserByPhoneExisted::class) // 验证手机号码用户是否存在
;

// 重置token接口
Route::patch('/auth', 'AuthController@resetToken');

// 用户注册
Route::post('/auth/register', 'AuthController@register')
    ->middleware(Middleware\CheckDeviceCodeExisted::class) // 验证设备号是否存在
    ->middleware(Middleware\VerifyPhoneNumber::class) // 验证手机号码是否正确
    ->middleware(Middleware\VerifyUserNameRole::class) // 验证用户名规则是否正确
    ->middleware(Middleware\CheckUserByNameNotExisted::class) // 验证用户名是否被占用
    ->middleware(Middleware\CheckUserByPhoneNotExisted::class) // 验证手机号码是否被占用
    ->middleware(Middleware\VerifyPhoneCode::class) // 验证验证码释放正确
;

// 找回密码
Route::patch('/auth/forgot', 'AuthController@forgotPassword')
    ->middleware(Middleware\VerifyPhoneNumber::class) // 验证手机号格式
    ->middleware(Middleware\CheckUserByPhoneExisted::class) // 验证手机号码用户是否存在
    ->middleware(Middleware\VerifyPhoneCode::class) // 验证手机号码验证码是否正确
;

// 地区接口
Route::get('/areas', 'AreaController@showAreas');

// 用户相关组
Route::prefix('users')
->middleware('auth:api')
->group(function () {
    // 修改用户资料
    Route::patch('/', 'UserController@profile')
        ->middleware(Middleware\ChangeUserAvatar::class)
        ->middleware(Middleware\ChangeUserCover::class)
        ->middleware(Middleware\ChangeUsername::class)
        ->middleware('role-permissions:user-update,你没有修改资料的权限');
    // 修改用户密码
    Route::patch('/password', 'UserController@resetPassword') // 设置控制器
        ->middleware(Middleware\VerifyPassword::class) // 验证用户密码是否正确
        ->middleware('role-permissions:password-update,你没有修改用户密码的权限');
    // 获取用户信息
    Route::post('/', 'UserController@get')
        ->middleware('role-permissions:user-view,你没有查看用户信息的权限');

    // 关注操作相关
    Route::post('/follow', 'FollowController@doFollow')
        ->middleware(Middleware\CheckUserExsistedByUserId::class)
        ->middleware(Middleware\CheckIsFollow::class)
        ->middleware('role-permissions:user-follow,你没有关注用户的权限');
    Route::delete('/unFollow', 'FollowController@doUnFollow')
        ->middleware(Middleware\CheckUserExsistedByUserId::class)
        ->middleware(Middleware\CheckIsFollowing::class)
        ->middleware('role-permissions:user-follow,你没有关注用户的权限');

    //查看指定用户关注状态
    Route::get('/followstatus', 'FollowController@getFollowStatus');

    // 获取我收到的评论
    Route::get('/mycomments', 'UserController@getMyComments');

    // 获取我收到的点赞
    Route::get('/mydiggs', 'UserController@getMyDiggs');

    // 刷新我收到的消息
    Route::get('/flushmessages', 'UserController@flushMessages');
});

// 点赞排行
Route::get('/diggsrank', 'UserController@diggsRank');

// 用户关注相关
Route::get('/follows/follows/{user_id}/{max_id?}', 'FollowController@follows');
Route::get('/follows/followeds/{user_id}/{max_id?}', 'FollowController@followeds');

// 获取一个附件资源
tap(Route::get('/storages/{storage}/{process?}', 'StorageController@get'), function (\Illuminate\Routing\Route &$route) {
    $route->setAction(array_merge($route->getAction(), [
        'middleware' => 'bindings',
    ]));
});

// 批量储存资源地址获取.
Route::get('/storages', 'StorageController@getStorageLinks');

// 附件储存相关
Route::prefix('storages')
->middleware('auth:api')
->group(function () {
    // 创建一个储存任务
    Route::post('/task', 'StorageController@create')
    ->middleware('role-permissions:storage-create,你没有上传附件的权限');
    // 完成一个任务上传通知
    Route::patch('/task/{storage_task_id}', 'StorageController@notice');
    // 删除一个上传任务附件
    Route::delete('/task/{storage_task_id}', 'StorageController@delete');
    // local storage api.
    Route::post('/task/{storage_task_id}', 'StorageController@upload')
        ->name('storage/upload');
});

//系统及配置相关
Route::prefix('/system')
->middleware('auth:api')
->group(function () {
    //意见反馈
    Route::post('/feedback', 'SystemController@createFeedback')
    ->middleware(Middleware\CheckFeedbackContentExisted::class)
    ->middleware('role-permissions:feedback,你没有意见反馈的权限');
    //获取系统会话列表
    Route::get('/conversations', 'SystemController@getConversations')
    ->middleware('role-permissions:conversations,你没有获取系统会话的权限');
});
//获取扩展包安装状态
Route::get('/system/component/status', 'SystemController@getComponentStatus');
//获取扩展包配置信息
Route::get('/system/component/configs', 'SystemController@getComponentConfig');
//关于我们
Route::get('/system/about', 'SystemController@about');
