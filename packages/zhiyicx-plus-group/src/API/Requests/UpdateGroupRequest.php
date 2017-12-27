<?php

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
//            'location' => 'required_with:longitude,latitude,geo_hash|string',
            'longitude' => 'required_with:latitude,geo_hash|string',
            'latitude' => 'required_with:longitude,geo_hash|string',
            'geo_hash' => 'required_with:longitude,latitude|string',
            'mode' => 'in:public,private,paid',
            'money' => 'required_if:mode,paid|integer',
        ];
    }
}
