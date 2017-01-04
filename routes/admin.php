<?php

// admin router.

Route::get('/', function () {
    return view('tets');
});

Route::group([
    'prefix' => 'vue',
], function ($router) {

    // 首页
    Route::get('/', function () {
        return view('admin.login');
    });

});
