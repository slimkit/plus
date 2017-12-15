<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\FormRequest\API2;

use Illuminate\Foundation\Http\FormRequest;

class StoreFeedComment extends FormRequest
{
    /**
     * authorization check.
     *
     * @return bool
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function authorize(): bool
    {
        return $this->user() ? true : false;
    }

    /**
     * get the validator rules.
     *
     * @return array
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function rules(): array
    {
        return [
            'reply_user' => ['nullable', 'integer', 'exists:users,id'],
            'body' => ['required', 'string', 'display_length:255'],
        ];
    }

    /**
     * Get the validator messages.
     *
     * @return array
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function messages(): array
    {
        return [
            'reply_user.reply_user' => '发送数据类型错误',
            'reply_user.exists' => '操作的用户不存在',
            'body.required' => '内容不能为空',
            'body.string' => '内容必须是字符串',
            'body.display_length' => '内容超出长度限制',
        ];
    }
}
