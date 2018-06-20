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

namespace Zhiyi\PlusGroup\API\Requests;

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
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function rules(): array
    {
        $id = (int) $this->segment(5);

        return [
            'name' => 'string|unique:groups,name,'.$id,
            'avatar' => 'image|max:2048',
            'tags' => 'array',
            'tags.*.id' => 'required|integer|exists:tags',
            'category_id' => 'integer|exists:group_categories,id',
            'location' => 'required_with:longitude,latitude,geo_hash|string|nullable',
            'longitude' => 'required_with:latitude,geo_hash|string|nullable',
            'latitude' => 'required_with:longitude,geo_hash|string|nullable',
            'geo_hash' => 'required_with:longitude,latitude|string|nullable',
            'mode' => 'in:public,private,paid',
            'money' => 'required_if:mode,paid|integer',
        ];
    }
}
