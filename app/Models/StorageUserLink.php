<?php

namespace Zhiyi\Plus\Models;

use Illuminate\Database\Eloquent\Model;

class StorageUserLink extends Model
{
    public function storage()
    {
        return $this->hasOne(Storage::class, 'storage_id');
    }
}
