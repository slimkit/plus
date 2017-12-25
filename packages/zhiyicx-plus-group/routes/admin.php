<?php

use Illuminate\Support\Facades\Route;
use Zhiyi\PlusGroup\Admin\Controllers as Admin;
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

Route::group(['prefix' => 'group-admin'], function (RouteRegisterContract $route) {

    // Home router.
    $route->get('/', Admin\HomeController::class.'@index')->name('plus-group:admin-home');

    // statistics
    $route->get('/statistics',  Admin\HomeController::class.'@statistics');

    // config setting
    $route->get('/config', Admin\HomeController::class.'@config');
    $route->patch('/config', Admin\HomeController::class.'@config');

    // category
    $route->get('/categories', Admin\CategoryController::class.'@index');
    $route->post('/categories', Admin\CategoryController::class.'@store');
    $route->delete('/categories/{category}', Admin\CategoryController::class.'@delete');
    $route->patch('/categories/{category}', Admin\CategoryController::class.'@update');

    // group
    $route->get('/groups', Admin\GroupController::class.'@index');
    $route->post('/groups', Admin\GroupController::class.'@store');
    $route->get('/groups/{group}', Admin\GroupController::class.'@show');
    $route->post('/groups/{group}', Admin\GroupController::class.'@update');
    $route->delete('/groups/{group}', Admin\GroupController::class.'@delete');
    $route->patch('/groups/{group}/recommend', Admin\GroupController::class.'@recommend');
    $route->patch('/groups/{group}/audit', Admin\GroupController::class.'@audit');
    $route->patch('/groups/{id}/restore', Admin\GroupController::class.'@restore');

    // protocol
    $route->get('/protocol', Admin\GroupProtocolController::class.'@get');
    $route->patch('/protocol', Admin\GroupProtocolController::class.'@set');

    // post
    $route->get('/posts', Admin\GroupPostController::class.'@index');
    $route->patch('/posts/{id}/restore', Admin\GroupPostController::class.'@restore');
    $route->delete('/groups/{groupId}/posts/{postId}', Admin\GroupPostController::class.'@delete');

    // pinned storePost
    $route->post('/pinned/posts/{post}', Admin\PinnedController::class.'@storePost');
    $route->patch('/pinned/posts/{post}/reject', Admin\PinnedController::class.'@rejectPost');
    $route->patch('/pinned/posts/{post}/accept', Admin\PinnedController::class.'@acceptPost');
    $route->patch('/pinned/posts/{post}/revocation', Admin\PinnedController::class.'@revocationPost');


    // group member
    $route->get('groups/{group}/members', Admin\GroupMemberController::class.'@members');
    $route->patch('members/{member}/role', Admin\GroupMemberController::class.'@role');
    $route->delete('members/{member}', Admin\GroupMemberController::class.'@delete');
    $route->patch('members/{member}/disable', Admin\GroupMemberController::class.'@disable');

    // post comments
    $route->get('comments', Admin\PostCommentController::class.'@index');
    $route->delete('comments/{comment}', Admin\PostCommentController::class.'@delete');

    // group recommend
    $route->get('recommends', Admin\GroupRecommendController::class.'@index');
    $route->delete('recommends/{recommend}', Admin\GroupRecommendController::class.'@remove');
    $route->patch('recommends/{recommend}/sort', Admin\GroupRecommendController::class.'@sort');

});
