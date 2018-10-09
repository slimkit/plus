<?php

use Illuminate\Support\Facades\Route;
use SlimKit\Plus\Packages\Blog\Web\Controllers as Web;
use Illuminate\Contracts\Routing\Registrar as RouteRegisterContract;

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

Route::get('/', Web\HomeController::class.'@index');
Route::group(['prefix' => 'blogs'], function (RouteRegisterContract $route) {
    
    // 我的博客
    $route
        ->get('me', Web\HomeController::class.'@me')
        ->name('blog:me')
    ;
    $route->post('me', Web\HomeController::class.'@createBlog');

    // 博客主页
    $route
        ->get('{blog}', Web\BlogController::class.'@show')
        ->name('blog:profile');
});
