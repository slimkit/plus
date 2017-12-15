<?php

namespace SlimKit\PlusCheckIn\Web\Controllers;

class HomeController
{
    public function index()
    {
        return trans('plus-checkin::messages.success');
    }
}
