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
    'prefix'    => 'admin',
    'namespace' => 'Admin',
    // 'middleware' => [],
], function ($router) {
    require base_path('routes/admin.php');
});
Route::group([
    'prefix' => 'imuser',
], function () {
    Route::get('/create/{user_id}', 'IMController@create')->name('imuser.create'); //注册聊天用户
    Route::get('/getImUser', 'IMController@getImUser')->name('imuser.get'); //获取聊天用户
});
