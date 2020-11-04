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
use Illuminate\Validation\Rule;

class UpdateGroup extends FormRequest
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
            'im_group_id' => 'required|integer',
            'groupname' => 'required|string',
            'group_face' => [
                'integer',
                'nullable',
                Rule::exists('file_withs', 'id')->where(function ($query) {
                    $query->where('channel', null);
                    $query->where('raw', null);
                }),
            ],
            'desc' => 'required|string',
            'numbers' => 'array',
            'public' => 'boolean|nullable',
            'members_only' => 'nullable',
            'allowinvites' => 'boolean|nullable',
            'new_owner_user' => [
                'integer',
                'nullable',
                Rule::exists('users', 'id')->where(function ($query) {
                    $query->where('deleted_at', null);
                }),
            ],
        ];
    }

    /**
     * return validation messages.
     */
    public function messages()
    {
        return [
            'im_group_id.required' => '群组ID不能为空',
            'groupname.required' => '群组名称不能为空',
            'desc.required' => '群组简介不能为空',
            'group_face.exists' => '文件不存在或已经被使用',
            'new_owner_user.exists' => '被转让用户不存在或已被删除',
        ];
    }
}
