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
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Music extends Model
{
    use SoftDeletes;
    use Relations\MusicHasLike;

    protected $hidden = ['pivot'];

    protected $table = 'musics';

    public function singer()
    {
        return $this->hasOne(MusicSinger::class, 'id', 'singer');
    }

    public function speciallinks()
    {
        return $this->hasMany(MusicSpecialLink::class, 'music_id', 'id');
    }

    public function musicSpecials()
    {
        return $this->belongsToMany(MusicSpecial::class, 'music_special_links', 'music_id', 'special_id');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function paidNode()
    {
        return $this->hasOne(PaidNode::class, 'raw', 'storage')
            ->where('channel', 'file');
    }

    public function formatStorage(int $user)
    {
        $storage = FileWith::with('paidNode')->find($this->storage);
        if (! $storage) {
            $this->storage = null;

            return $this;
        }

        $file['id'] = $storage->id;
        if ($storage->paidNode !== null) {
            $file['amount'] = $storage->paidNode->amount;
            $file['type'] = $storage->paidNode->extra;
            $file['paid'] = $storage->paidNode->paid($user);
            $file['paid_node'] = $storage->paidNode->id;
        }
        $this->storage = $file;

        return $this;
    }
}
