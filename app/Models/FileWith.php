<?php

namespace Zhiyi\Plus\Models;

use Zhiyi\Plus\Models\File;
use Illuminate\Database\Eloquent\Model;

class FileWith extends Model
{
    /**
     * has file.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function file()
    {
        return $this->hasOne(File::class, 'file_id', 'id');
    }
}
