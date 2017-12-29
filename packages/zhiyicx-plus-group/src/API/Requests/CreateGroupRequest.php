<?php

namespace Zhiyi\PlusGroup\API\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateGroupRequest extends FormRequest
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
        return [
            'avatar' => 'required|image|max:2048',
            'name' => 'required|string|unique:groups,name',
            'tags' => 'required|array',
            'tags.*.id' => 'required|integer|exists:tags',
            'location' => 'required_with:longitude,latitude,geo_hash|string',
            'longitude' => 'required_with:location,latitude,geo_hash|string',
            'latitude' => 'required_with:location,longitude,geo_hash|string',
            'geo_hash' => 'required_with:location,longitude,latitude|string',
            'mode' => 'required|in:public,private,paid',
            'money' => 'required_if:mode,paid|integer|min:1',
        ];
    }

    /**
     * 错误消息.
     *
     * @return array
     * @author BS <414606094@qq.com>
     */
    public function messages(): array
    {
        return [
            'avatar.required' => '必须上传头像',
            'name.required' => '圈子名称不能为空',
            'tags.required' => '请选择标签',
            'mode.string' => '请选择圈子类型',
        ];
    }
}
