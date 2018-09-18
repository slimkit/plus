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

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models;

use Zhiyi\Plus\Models\Comment;
use Zhiyi\Plus\Models\FileWith;
use Zhiyi\Plus\Models\PaidNode;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MusicSpecial extends Model
{
    use SoftDeletes;

    protected $hidden = ['pivot'];

    protected $table = 'music_specials';

    public function musics()
    {
        return $this->belongsToMany(Music::class, 'music_special_links', 'special_id', 'id');
    }

    public function storage()
    {
        return $this->hasOne(FileWith::class, 'id', 'storage')->select('id', 'size');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * 专辑收藏记录.
     *
     * @author bs<414606094@qq.com>
     */
    public function collections()
    {
        return $this->hasMany(MusicCollection::class, 'special_id', 'id');
    }

    /**
     * 专辑付费节点.
     *
     * @author bs<414606094@qq.com>
     */
    public function paidNode()
    {
        return $this->hasOne(PaidNode::class, 'raw', 'id')->where('channel', 'music');
    }

    /**
     * 处理付费节点信息.
     *
     * @author bs<414606094@qq.com>
     * @param  int    $user [description]
     * @return mix
     */
    public function formatPaidNode(int $user)
    {
        if ($this->paidNode !== null) {
            $paidNode = [
                'paid' => $this->paidNode->paid($user),
                'node' => $this->paidNode->id,
                'amount' => $this->paidNode->amount,
            ];
            unset($this->paidNode);
            $this->paid_node = $paidNode;
        }

        return $this;
    }

    /**
     * 验证某个用户是否收藏了某个专辑.
     *
     * @author bs<414606094@qq.com>
     * @param  [int]  $user
     * @return bool
     */
    public function hasCollected(int $user): bool
    {
        $cacheKey = sprintf('music-special-collected:%s,%s', $this->id, $user);

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $status = $this->collections()->where('user_id', $user)->first() !== null;
        Cache::forever($cacheKey, $status);

        return $status;
    }
}
