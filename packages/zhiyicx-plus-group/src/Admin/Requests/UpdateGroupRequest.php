<?php

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2017 Chengdu ZhiYiChuangXiang Technology Co., Ltd.     |
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

namespace Zhiyi\PlusGroup\Admin\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGroupRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     * @author hh <915664508@qq.com>
     */
    public function rules(): array
    {
        $id = (int) $this->segment(3);

        return [
            'name' => 'required|string|unique:groups,name,'.$id,
            'avatar' => 'image|max:2048',
            'category_id' => 'required|integer|exists:group_categories,id',
            'tags' => 'required|string',
            'tags.*' => 'required|integer|exists:tags,id',
            'longitude' => 'required_with:latitude|string',
            'latitude' => 'required_with:longitude|string',
            'mode' => 'required|in:public,private,paid',
            'money' => 'required_if:mode,paid|integer',
            'summary' => 'required|max:255',
            'notice' => 'required|max:2000',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     * @author hh <915664508@qq.com>
     */
    public function messages(): array
    {
        return [
            'name.required' => '圈子名称必须填写',
            'name.unique' => '圈子名称已经存在',
            'avatar.image' => '头像文件格式不正确',
            'avatar.max' => '头像不能超过2M',
            'category_id.required' => '圈子分类必须选择',
            'category_id.exists' => '圈子分类错误',
            'tags.required' => '请添加标签',
            'mode.required' => '请选择圈子类别',
            'money.required_if' => '收费圈子请填写金额',
            'summary.required' => '请填写圈子简介',
            'summary.max' => '简介最大长度255字符',
            'notice.required' => '请填写圈子公告',
            'notice.max' => '公告最大长度2000字符',
        ];
    }
}
