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

/**
 * Zhiyi\Plus\Models\FileWith.
 *
 * @property int $id
 * @property int $file_id 文件ID
 * @property int $user_id 用户ID
 * @property string|null $channel 记录频道
 * @property int|null $raw 原始频道关联信息
 * @property string|null $size 图片尺寸，目标文件如果是图片的话则存在。便于客户端提前预设盒子
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \Zhiyi\Plus\Models\File|null $file
 * @property-read string $pay_index
 * @property-read \Zhiyi\Plus\Models\PaidNode|null $paidNode
 *
 * @method static Builder|FileWith newModelQuery()
 * @method static Builder|FileWith newQuery()
 * @method static Builder|FileWith query()
 * @method static Builder|FileWith whereChannel($value)
 * @method static Builder|FileWith whereCreatedAt($value)
 * @method static Builder|FileWith whereDeletedAt($value)
 * @method static Builder|FileWith whereFileId($value)
 * @method static Builder|FileWith whereId($value)
 * @method static Builder|FileWith whereRaw($value)
 * @method static Builder|FileWith whereSize($value)
 * @method static Builder|FileWith whereUpdatedAt($value)
 * @method static Builder|FileWith whereUserId($value)
 * @mixin \Eloquent
 */
class FileWith extends Model
{
    /**
     * 可以被批量赋值的属性.
     *
     * @var array
     */
    protected $fillable = ['file_id', 'user_id', 'channel', 'raw', 'size'];

    public static function boot()
    {
        parent::boot();
        static::addGlobalScope('paidNode', function (Builder $query) {
            $query->with('paidNode');
        });
    }

    public function getPayIndexAttribute(): string
    {
        return sprintf('file:%d', $this->id);
    }

    /**
     * has file.
     *
     * @return HasOne
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function file()
    {
        return $this->hasOne(File::class, 'id', 'file_id');
    }

    /**
     * 获取付费节点.
     *
     * @return HasOne
     *
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function paidNode()
    {
        return $this->hasOne(PaidNode::class, 'raw', 'id')
            ->where('channel', 'file');
    }
}
