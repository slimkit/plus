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
 * Zhiyi\Plus\Models\CurrencyTypeTransform.
 *
 * @property int $form_type_id 原始货币类型
 * @property int $to_type_id 目标货币类型
 * @property int $form_sum 原始货币数量
 * @property int $to_sum 目标货币数量
 *
 * @method static \Illuminate\Database\Eloquent\Builder|CurrencyTypeTransform newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CurrencyTypeTransform newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CurrencyTypeTransform query()
 * @method static \Illuminate\Database\Eloquent\Builder|CurrencyTypeTransform whereFormSum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CurrencyTypeTransform whereFormTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CurrencyTypeTransform whereToSum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CurrencyTypeTransform whereToTypeId($value)
 * @mixin \Eloquent
 */
class CurrencyTypeTransform extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'form_type_id';

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'int';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;
}
