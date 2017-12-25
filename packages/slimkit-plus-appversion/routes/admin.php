<?php

use Illuminate\Support\Facades\Route;
use Slimkit\PlusAppversion\Admin\Controllers as Admin;
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

Route::group(['prefix' => 'admin/plus-appversion'], function (RouteRegisterContract $route) {
    // Home router.
    $route->get('/', Admin\HomeController::class.'@home')->name('plus-appversion:admin-home');
    $route->get('/current', Admin\HomeController::class.'@currentVersion')->name('plus-appversion:current-version');
    $route->get('/versions', Admin\HomeController::class.'@index')->name('plus-appversion:admin-detail');
    $route->post('/', Admin\HomeController::class.'@store');
    $route->delete('/{clientVersion}', Admin\HomeController::class.'@delete');
    $route->post('/upload', Admin\HomeController::class.'@storage')->name('plus-appversion:admin-upload');
    $route->get('/status', Admin\HomeController::class.'@status')->name('plus-appversion:admin-status');
    $route->patch('/status', Admin\HomeController::class.'@update')->name('plus-appversion:admin-status-update');
});
