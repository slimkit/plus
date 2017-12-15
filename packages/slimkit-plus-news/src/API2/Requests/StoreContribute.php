<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentNews\API2\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpKernel\Exception\HttpException;

class StoreContribute extends FormRequest
{
    /**
     * Store news contribute authorize.
     *
     * @return bool
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function authorize(): bool
    {
        if (config('news.contribute.verified')) {
            return $this->user()->verified === null ? false : true;
        }

        return true;
    }

    /**
     * Handle a failed authorization attempt.
     *
     * @return void
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    protected function failedAuthorization()
    {
        throw new HttpException('403', '你没有权限投稿');
    }

    /**
     * Get the validate rules.
     *
     * @return array
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:40'],
            'subject' => ['max:400'],
            'content' => ['required', 'string'],
            'image' => ['nullable', 'integer', Rule::exists('file_withs', 'id')->whereNull('channel')->whereNull('raw')],
            'from' => ['nullable', 'string'],
            'author' => ['nullable', 'string'],
            'tags' => ['required'],
        ];
    }

    /**
     * Get the validate messages.
     *
     * @return array
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function messages(): array
    {
        return [
            'title.required' => '标题不能为空',
            'title.string' => '标题必须是字符串',
            'title.max' => '标题超出字数限制',
            'subject.max' => '概述超出字数限制',
            'content.required' => '内容不能为空',
            'content.string' => '内容必须是字符串',
            'image.required' => '请上传缩略图',
            'image.exists' => '缩略图不存在',
            'from.string' => '来源必须是文字',
            'author.string' => '作者必须是文字',
            'tags.required' => '至少选择一个标签',
        ];
    }
}
