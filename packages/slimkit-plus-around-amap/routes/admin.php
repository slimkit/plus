<?php

use Illuminate\Support\Facades\Route;
use SlimKit\PlusAroundAmap\Admin\Controllers as Admin;
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

Route::group(['prefix' => 'around-amap/admin'], function (RouteRegisterContract $route) {

    // Home router.
    $route->get('/', Admin\HomeController::class.'@index')->name('around-amap:admin-home');
    $route->post('/save', Admin\HomeController::class.'@save')->name('around-amap:admin-save');
});
