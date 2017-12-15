<?php

namespace SlimKit\PlusSocialite\Web\Controllers;

class HomeController
{
    public function index()
    {
        return trans('plus-socialite::messages.success');
    }
}
