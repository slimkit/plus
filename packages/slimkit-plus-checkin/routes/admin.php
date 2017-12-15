<?php

use Illuminate\Support\Facades\Route;
use SlimKit\PlusCheckIn\Admin\Controllers as Admin;
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
    'prefix' => 'slimkit/checkin',
    'middleware' => 'ability:admin: checkin config',
], function (RouteRegisterContract $route) {

    // Home router.
    $route->get('/', Admin\HomeController::class.'@index')->name('checkin:admin-home');

    // Store
    $route->put('/', Admin\HomeController::class.'@store')->name('checkin:store-config');
});
