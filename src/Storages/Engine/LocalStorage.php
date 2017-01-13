<?php

namespace Ts\Storages\Engine;

use App\Models\StorageTask;
use App\Models\User;
use Ts\Interfaces\Storage\StorageEngineInterface;

class LocalStorage implements StorageEngineInterface
{
    public function createStorageTask(StorageTask $storateTask, User $user)
    {
        $token = $user->tokens()->orderByDesc()->first();
        if (!$token) {
            throw new \Exception('No authentication information associated with the user was found.');
        }

        $storateTask->save();

        return [
            'uri'     => route('storage/upload', [$storateTask->id]),
            'method'  => 'PUT',
            'storage_task_id' => $storateTask->id,
            'headers' => [
                'ACCESS-TOKEN' => $token->token,
            ],
            'options' => [],
        ];
    }
}
