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

namespace SlimKit\PlusQuestion\API2\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
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
            'body' => 'required|string|min:1|max:255',
            'reply_user' => 'not_in:'.$this->user()->id,
            'comment_mark' => [
                Rule::notIn([Cache::get('comment_mark_'.$this->input('comment_mark'))])
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function messages(): array
    {
        return [
            'body.requered' => '评论内容不能为空',
            'body.max' => '不能超过255个字',
            'reply_user.not_in' => '不能回复自己',
        ];
    }

    /*
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    // public function attributes(): array
    // {
    //     return [
    //         'subject' => trans('plus-question::questions.attributes.subject'),
    //         'body' => trans('plus-question::questions.attributes.body'),
    //         'topics' => trans('plus-question::questions.attributes.topics'),
    //         'topics.*.id' => trans('plus-question::questions.attributes.topics'),
    //         'anonymity' => trans('plus-question::questions.attributes.anonymity'),
    //         'look' => trans('plus-question::questions.attributes.look'),
    //         'invitations.*.user' => trans('plus-question::questions.attributes.user'),
    //     ];
    // }
}
