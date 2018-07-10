<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2018 Chengdu ZhiYiChuangXiang Technology Co., Ltd.     |
 * +----------------------------------------------------------------------+
 * | This source file is subject to version 2.0 of the Apache license,    |
 * | that is bundled with this package in the file LICENSE, and is        |
 * | available through the world-wide-web at the following url:           |
 * | http://www.apache.org/licenses/LICENSE-2.0.html                      |
 * +----------------------------------------------------------------------+
 * | Author: Slim Kit Group <master@zhiyicx.com>                          |
 * | Homepage: www.thinksns.com                                           |
 * +----------------------------------------------------------------------+
 */

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models;

use Zhiyi\Plus\Models\Tag;
use Zhiyi\Plus\Models\User;
use Zhiyi\Plus\Models\Report;
use Zhiyi\Plus\Models\Comment;
use Zhiyi\Plus\Models\FileWith;
use Zhiyi\Plus\Models\BlackList;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class News extends Model
{
    use SoftDeletes,
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
     * @return   [type]              [description]
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
     */
    public function scopeByAudit(Builder $query): Builder
    {
        return $query->where('audit_status', 0);
    }

    /**
     *  资讯拥有的置顶评论.
     *  @author Wayne < qiaobinloverabbi@gmail.com >
     *  @return [type]
     */
    public function pinnedComments()
    {
        return $this->belongsToMany(Comment::class, 'news_pinneds', 'target', 'raw')
            ->where('channel', 'news:comment');
    }

    /**
     *  是否置顶.
     *  @author Wayne < qiaobinloverabbi@gmail.com >
     *  @return [type] [description]
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
        return $this->hasOne(NewsApplyLog::class, 'id', 'news_id');
    }

    /**
     * 被举报记录.
     *
     * @return morphMany
     * @author BS <414606094@qq.com>
     */
    public function reports()
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
