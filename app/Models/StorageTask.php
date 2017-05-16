<?php

namespace Zhiyi\Plus\Models;

use Illuminate\Database\Eloquent\Model;

class StorageTask extends Model
{
    public function storage()
    {
        return $this->hasOne(Storage::class, 'hash', 'hash');
    }
}
