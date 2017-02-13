<?php

use Zhiyi\Plus\Http\Middleware;

// admin router.

Route::get('/', function () {
    return view('admin');
});
