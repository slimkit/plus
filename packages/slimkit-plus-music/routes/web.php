<?php

Route::any('/component-example', 'ExampleWebController@example');
Route::any('/component-example/admin', 'ExampleWebController@admin')
    ->middleware('auth:web')
    ->name('example.admin');
    // ->middleware('role:admin');
