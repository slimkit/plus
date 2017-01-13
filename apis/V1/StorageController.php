<?php

namespace App\Http\Controllers\APIs\V1;

use App\Http\Controllers\Controller;
use App\Models\StorageTask;
use Illuminate\Http\Request;
use Ts\Storages\Storage;

class StorageController extends Controller
{
    public function createStorageTask(Request $request, string $hash, string $origin_filename)
    {
        $user = $request->attributes->get('user');
        $storage = app(Storage::class)->createStorageTask($user, $origin_filename, $hash);
        var_dump($storage);
        exit;
    }

    public function upload(Request $request, int $storage_task_id)
    {
        $storageTask = StorageTask::find($storage_task_id);
        var_dump($storageTask);
    }
}
