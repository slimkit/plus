<?php

namespace SlimKit\PlusSocialite\Admin\Controllers;

class HomeController
{
    public function index()
    {
        return trans('plus-socialite::messages.success');
    }
}
