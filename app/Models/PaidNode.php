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
 * Zhiyi\Plus\Models\PaidNode
 *
 * @property int $id 付费记录ID
 * @property string $channel 付费频道
 * @property int $raw 付费原始信息
 * @property string $subject 付费主题
 * @property string $body 付费内容详情
 * @property int $amount 付费金额
 * @property int|null $user_id 用户ID，主要用于排除付费用户。
 * @property string|null $extra 拓展信息
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Zhiyi\Plus\Models\User|null $user
 * @property-read \Illuminate\Database\Eloquent\Collection|\Zhiyi\Plus\Models\User[] $users
 * @property-read int|null $users_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Zhiyi\Plus\Models\Wallet[] $wallet
 * @property-read int|null $wallet_count
 * @method static \Illuminate\Database\Eloquent\Builder|PaidNode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaidNode newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaidNode query()
 * @method static \Illuminate\Database\Eloquent\Builder|PaidNode whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaidNode whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaidNode whereChannel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaidNode whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaidNode whereExtra($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaidNode whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaidNode whereRaw($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaidNode whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaidNode whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaidNode whereUserId($value)
 * @mixin \Eloquent
 */
class PaidNode extends Model
{
    use Relations\PaidNodeHasUser;
}
