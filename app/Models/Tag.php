<?php

namespace Zhiyi\Plus\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at', 'pivot'];

    /**
     * Has the category of tag.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function category()
    {
        return $this->hasOne(TagCategory::class, 'id', 'tag_category_id');
    }

    /**
     * 统计使用了多少次
     */
    public function taggable()
    {
        return $this->hasMany(Taggable::class, 'tag_id', 'id');
    }
}
