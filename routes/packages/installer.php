<?php

use Illuminate\Support\Facades\Route;
use Zhiyi\Plus\Packages\Installer\Middleware;
use Zhiyi\Plus\Packages\Installer\Controllers;
use Illuminate\Contracts\Routing\Registrar as RouteRegisterContract;

Route::view('/', 'installer');

Route::post('/password', Controllers\InstallController::class.'@verifyPassword');
Route::get('/license', Controllers\InstallController::class.'@license');
Route::group(['middleware' => Middleware\VerifyInstallationPassword::class], function (RouteRegisterContract $route) {
    $route->post('/check', Controllers\InstallController::class.'@check');
    $route->post('/info', Controllers\InstallController::class.'@getInfo');
    $route->put('/info', Controllers\InstallController::class.'@setInfo');
});
