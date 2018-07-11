<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2018 Chengdu ZhiYiChuangXiang Technology Co., Ltd.     |
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

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentNews\API2\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Http\FormRequest;

class StoreNewsComment extends FormRequest
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
            'comment_mark' => [
                Rule::notIn([Cache::get('comment_mark_'.$this->input('comment_mark'))]),
            ],
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
            'reply_user.exists' => '被回复用户不存在',
            'body.required' => '评论内容不能为空',
            'body.string' => '评论内容必须是文字',
            'body.display_length' => '内容超出长度限制',
        ];
    }
}
