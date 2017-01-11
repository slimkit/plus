<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('tets');
});

Route::group([
    'prefix' => 'admin',
    'namespace' => 'Admin',
    // 'middleware' => [],
], function ($router) {
    require base_path('routes/admin.php');
});

Route::group([
    'prefix' => 'imuser',
], function () {
    Route::post('/create', 'ImUserController@imUsercreate')->name('imuser.create');//注册IM用户
    Route::delete('/delete', 'ImUserController@imUserDelete')->name('imuser.delete');//删除IM用户
    Route::get('/testDelete', 'ImUserController@testDelete');//测试删除用户
});
