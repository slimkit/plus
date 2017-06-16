<?php

namespace Zhiyi\Plus\Models;

use Illuminate\Database\Eloquent\Model;

class FileWith extends Model
{
    public function getPayIndexAttribute(): string
    {
        return sprintf('file:%d', $this->id);
    }

    /**
     * has file.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function file()
    {
        return $this->hasOne(File::class, 'id', 'file_id');
    }

    /**
     * has pay publish.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function pay()
    {
        return $this->hasOne(PayPublish::class, 'index', 'pay_index');
    }
}
