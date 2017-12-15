<?php

use Illuminate\Support\Facades\Route;
use SlimKit\PlusID\Admin\Controllers as Admin;
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

Route::group(['prefix' => 'slimkit/plus-id'], function (RouteRegisterContract $route) {

    // Home router.
    $route->get('/', Admin\HomeController::class.'@index')->name('plus-id:admin-home');

    // Get all clients.
    $route->get('/clients', Admin\ClientsController::class.'@index');

    // Store a client.
    $route->post('/clients', Admin\ClientsController::class.'@store');

    // Destory a client.
    $route->delete('/clients/{client}', Admin\ClientsController::class.'@destroy');

    // Update a client.
    $route->patch('/clients/{client}', Admin\ClientsController::class.'@update');
});
