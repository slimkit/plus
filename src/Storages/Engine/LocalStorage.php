<?php

namespace Ts\Storages\Engine;

use App\Models\StorageTask;
use Ts\Interfaces\Storage\StorageEngineInterface;

class LocalStorage implements StorageEngineInterface
{
    public function createStorageTask(StorageTask $storateTask)
    {
        $storateTask->save();
        return [
            'uri' => route('storage/upload'),
            'method' => 'PUT',
            'options' => [],
        ];
    }
}
