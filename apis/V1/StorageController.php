<?php

namespace App\Http\Controllers\APIs\V1;

use App\Http\Controllers\Controller;

class StorageController extends Controller
{
    public function createStorageTask(string $hash, string $origin_filename)
    {
        var_dump($hash, $origin_filename);
        exit;
    }
}
