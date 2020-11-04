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

namespace Zhiyi\Plus\EaseMobIM\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckGroup extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'groupname' => 'required|string',
            'desc' => 'required|string',
            'numbers' => 'string',
            'public' => 'boolean|nullable',
            'members_only' => 'nullable',
            'allowinvites' => 'boolean|nullable',
        ];
    }

    /**
     * return validation messages.
     */
    public function messages()
    {
        return [
            'groupname.required' => '群组名称不能为空',
            'desc.required' => '群组简介不能为空',
        ];
    }
}
