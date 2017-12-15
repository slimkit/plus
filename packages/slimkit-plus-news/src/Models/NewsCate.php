<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models;

use Illuminate\Database\Eloquent\Model;

class NewsCate extends Model
{
    protected $table = 'news_cates';
    public $timestamps = false;

    /**
     * Has news.
     *
     * @return null|
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function news()
    {
        return $this->hasMany(News::class, 'cate_id', 'id');
    }
}
