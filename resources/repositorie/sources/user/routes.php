<?php
use function Zhiyi\PlusComponentWeb\view as webview;
Route::get('/web', function () {
    return webview('index');
});