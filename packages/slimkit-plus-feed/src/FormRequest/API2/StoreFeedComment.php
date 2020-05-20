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

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\FormRequest\API2;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;

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
            'reply_user.exists' => '操作的用户不存在',
            'body.required' => '内容不能为空',
            'body.string' => '内容必须是字符串',
            'body.display_length' => '内容超出长度限制',
        ];
    }
}
