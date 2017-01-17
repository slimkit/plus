<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StorageUserLink extends Model
{
    public function storage()
    {
        return $this->hasOne(Storage::class, 'storage_id');
    }
}
