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

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SingerUpdate extends FormRequest
{
    public function authorize()
    {
        return $this->user();
    }

    public function rules(): array
    {
        return [
            'name' => [
                'bail',
                'required',
                'max:30',

            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => '歌手名必填',
            'name.max' => '歌手名过长，最多30字',
            'name.unique' => '已经添加过该歌手',
        ];
    }

    public function failedAuthorization()
    {
        throw new AuthorizationException('没有添加歌手的权限');
    }
}
