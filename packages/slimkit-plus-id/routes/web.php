<?php

use Illuminate\Support\Facades\Route;
use SlimKit\PlusID\Web\Controllers as Web;
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

Route::group(['prefix' => 'plus-id'], function (RouteRegisterContract $route) {
    $route->any('/clients/{client}', Web\HomeController::class.'@index');

    // Home Router.
    // $route->get('/', Web\HomeController::class.'@index');

    // resolve
    // $route->get('/clients/{client}', Web\AuthController::class.'@resolve');

    // login
    // $route->any('/clients/{client}/login', Web\AuthController::class.'@login');
});
