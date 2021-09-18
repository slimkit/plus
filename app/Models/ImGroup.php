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

use Illuminate\Database\Eloquent\Model;

/**
 * Zhiyi\Plus\Models\ImGroup.
 *
 * @property int $id 表ID
 * @property string $im_group_id 环信群组ID
 * @property int|null $user_id 用户ID
 * @property string|null $group_face 群组头像
 * @property int|null $type 类型：0-群组 1-聊天室
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \Zhiyi\Plus\Models\FileWith|null $face
 * @property-read \Zhiyi\Plus\Models\User|null $owner
 *
 * @method static \Illuminate\Database\Eloquent\Builder|ImGroup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ImGroup newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ImGroup query()
 * @method static \Illuminate\Database\Eloquent\Builder|ImGroup whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImGroup whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImGroup whereGroupFace($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImGroup whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImGroup whereImGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImGroup whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImGroup whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ImGroup whereUserId($value)
 * @mixin \Eloquent
 */
class ImGroup extends Model
{
    public $table = 'im_group';

    public function owner()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function face()
    {
        return $this->hasOne(FileWith::class, 'id', 'group_face');
    }
}
