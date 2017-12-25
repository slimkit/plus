<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class MusicAdd extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'storage' => [
                Rule::exists('file_withs', 'id')->where(function ($query) {
                    $query->where('channel', null);
                    $query->where('raw', null);
                }),
            ],
            'title' => 'required|max:20',
            'singer' => 'required|numeric',
            'special' => 'array',
            'listen' => 'numeric|min:0.01',
            'download' => 'numeric|min:0.01',
            'paid_node' => '',
            'sort' => 'min:0',
            'last_time' => 'nullable|numeric|min:0'
        ];
    }

    /**
     * Get the validation message that apply to the request.
     *
     * @return array
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function messages(): array
    {
        return [
            'file.required' => '没有上传文件或者上传错误',
            'file.max' => '文件上传超出服务器限制',
            'file.file' => '文件上传失败',
            'file.mimes' => '文件上传格式错误',
            'singer.required' => '请填写歌曲所属歌手'
        ];
    }
}
