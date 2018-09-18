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

namespace SlimKit\PlusAroundAmap\Admin\Requests;

class StoreAmapRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function rules(): array
    {
        return [
            'amap-sig' => 'required|string',
            'amap-tableid' => 'required|string',
            'amap-key' => 'required|string',
            'amap-jssdk' => 'required|string',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function messages(): array
    {
        return [
            'amap-jssdk.required' => '请输入 JS SDK 地址',
            'amap-jssdk.string' => 'JS SDK 地址必须是字符串',
            'amap-sig.required' => '请输入应用密钥',
            'amap-sig.string' => '应用密钥必须是字符串',
            'amap-key.required' => '请输入高德 Web 服务 Key',
            'amap-key.string' => '高德 Web 服务 Key必须是字符串',
            'amap-tableid.required' => '请输入自定义地图 Table ID',
            'amap-tableid.string' => '自定义地图 Table ID地址必须是字符串',
        ];
    }
}
