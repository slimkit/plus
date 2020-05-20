<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2016-Present ZhiYiChuangXiang Technology Co., Ltd.     |
 * +----------------------------------------------------------------------+
 * | This source file is subject to enterprise private license, that is   |
 * | bundled with this package in the file LICENSE, and is available      |
 * | through the world-wide-web at the following url:                     |
 * | https://github.com/slimkit/plus/blob/master/LICENSE                  |
 * +----------------------------------------------------------------------+
 * | Author: Slim Kit Group <master@zhiyicx.com>                          |
 * | Homepage: www.thinksns.com                                           |
 * +----------------------------------------------------------------------+
 */

namespace Zhiyi\Plus\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Certification extends Model
{
    use Concerns\HasAvatar;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'array',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['icon'];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['category'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['certification_name', 'user_id', 'data', 'status'];

    public static function boot()
    {
        parent::boot();
        static::addGlobalScope('category', function (Builder $builder) {
            $builder->with('category');
        });
    }

    /**
     * Get icon url.
     *
     * @return string|null
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getIconAttribute()
    {
        return $this->avatar();
    }

    /**
     * Get avatar trait.
     *
     * @return string|int
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getAvatarKey()
    {
        return $this->certification_name;
    }

    /**
     * avatar extensions.
     *
     * @return array
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getAvatarExtensions(): array
    {
        return ['png', 'jpg', 'jpeg', 'bmp'];
    }

    /**
     * Avatar prefix.
     *
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getAvatarPrefix(): string
    {
        return 'certifications';
    }

    /**
     * Has certification caregory.
     *
     * @return HasOne
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function category()
    {
        return $this->hasOne(CertificationCategory::class, 'name', 'certification_name');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
