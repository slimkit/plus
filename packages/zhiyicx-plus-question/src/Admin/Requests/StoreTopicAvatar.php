<?php

namespace SlimKit\PlusQuestion\Admin\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class StoreTopicAvatar extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'avatar' => [
                'required',
                'image',
                'max:'.$this->getMaxFilesize() / 1024,
                'dimensions:min_width=100,min_height=100,max_width=500,max_height=500,ratio=1/1',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'avatar.required' => '请上传头像.',
            'avatar.image' => '头像必须是 png/jpeg/bmp/gif/svg 图片',
            'avatar.max' => sprintf('头像尺寸必须小于%sMB', $this->getMaxFilesize() / 1024 / 1024),
            'avatar.dimensions' => '头像必须是正方形，宽高必须在 100px - 500px 之间',
        ];
    }

    protected function getMaxFilesize()
    {
        return UploadedFile::getMaxFilesize();
    }
}
