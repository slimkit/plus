<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentPc\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Navigation extends Model
{

    protected $table = 'navigation';

    protected $primaryKey = 'id';

    protected $fillable = ['name', 'app_name' ,'url', 'target', 'status', 'position', 'parent_id', 'order_sort'];

    /**
     * 获取位置导航.
     *
     * @param  Builder $query
     * @param  int     $pos   0-头部 1-底部
     * @return Builder
     */
    public function scopeByPos(Builder $query, int $pos)
    {
    	return $query->where('position', $pos);
    }

    public function scopeByPid(Builder $query, int $pid): Builder
    {
        return $query->where('parent_id', $pid);
    }

    public function parent()
    {
        return $this->hasOne(__CLASS__, 'id', 'parent_id');
    }

    public function items()
    {
        return $this->hasMany(__CLASS__, 'parent_id');
    }
}
