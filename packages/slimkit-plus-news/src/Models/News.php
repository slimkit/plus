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

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Zhiyi\Plus\Models\BlackList;
use Zhiyi\Plus\Models\Comment;
use Zhiyi\Plus\Models\FileWith;
use Zhiyi\Plus\Models\Report;
use Zhiyi\Plus\Models\Tag;
use Zhiyi\Plus\Models\User;

class News extends Model
{
    use SoftDeletes,
        HasFactory,
        Relations\NewsHasLike,
        Relations\NewsHasReward,
        Relations\NewsHasCollection;

    protected $table = 'news';

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = ['storage', 'deleted_at', 'cate_id'];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['category', 'image', 'pinned'];

    /**
     *  Has catrgory.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function category()
    {
        return $this->hasOne(NewsCate::class, 'id', 'cate_id');
    }

    /**
     * black list of current user.
     * @Author   Wayne
     * @DateTime 2018-04-14
     * @Email    qiaobin@zhiyicx.com
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function blacks()
    {
        return $this->hasMany(BlackList::class, 'target_id', 'user_id');
    }

    /**
     * Has image.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function image()
    {
        return $this->hasOne(FileWith::class, 'id', 'storage')
            ->select('id', 'size');
    }

    public function newsCount()
    {
        // 作者文章数
        return $this->hasMany(self::class, 'user_id', 'user_id');
    }

    public function user()
    {
        // 文章作者用户data信息
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     *  文章对应的评论.
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * 筛选已审核文章.
     * @param  Builder  $query
     * @return Builder
     */
    public function scopeByAudit(Builder $query): Builder
    {
        return $query->where('audit_status', 0);
    }

    /**
     *  资讯拥有的置顶评论.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     * @author Wayne < qiaobinloverabbi@gmail.com >
     */
    public function pinnedComments()
    {
        return $this->belongsToMany(Comment::class, 'news_pinneds', 'target', 'raw')
            ->where('channel', 'news:comment');
    }

    /**
     *  是否置顶.
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     * @author Wayne < qiaobinloverabbi@gmail.com >
     */
    public function pinned()
    {
        return $this->hasOne(NewsPinned::class, 'target', 'id')
            ->where('channel', 'news');
    }

    /**
     * Has tags of the news.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable', 'taggables')
            ->withTimestamps();
    }

    /**
     * 资讯关联的删除申请记录.
     *
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function applylog()
    {
        return $this->hasOne(NewsApplyLog::class, 'news_id', 'id');
    }

    /**
     * 被举报记录.
     *
     * @return morphMany
     * @author BS <414606094@qq.com>
     */
    public function reports(): morphMany
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    public function getImagesAttribute($value)
    {
        return $value ? json_decode($value, true) : null;
    }

    public function setImagesAttribute(Collection $images)
    {
        $this->attributes['images'] = $images->isEmpty() ? null : json_encode($images);
    }
}
