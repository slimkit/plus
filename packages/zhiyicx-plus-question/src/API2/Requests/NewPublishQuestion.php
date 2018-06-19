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

use Illuminate\Foundation\Http\FormRequest;

class NewPublishQuestion extends FormRequest
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
        $currency = $this->user()->currency()->firstOrCreate(['type' => 1], ['sum' => 0]);

        return [
            'subject' => ['bail', 'required', 'min:2', 'max:50', 'regex:/[?|？]$/is'],
            'body' => 'nullable|string',
            'anonymity' => 'nullable|integer|in:0,1',
            'amount' => 'nullable|integer|max:'.$currency->sum,
            'look' => 'nullable|integer|in:0,1',
            'topics' => 'bail|required|array|between:1,5',
            'topics.*.id' => 'bail|required_with:topics|distinct|exists:topics,id',
            'invitations' => 'nullable|array',
            'invitations.*.user' => 'bail|required_with:invitations|distinct|not_in:'.$this->user()->id.'|exists:users,id',
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
            'subject.regex' => trans('plus-question::questions.Attribute must end with a question mark'),
            'amount.max' => trans('plus-question::questions.Insufficient balance'),
            'invitations.*.user.not_in' => trans('plus-question::questions.Can not invite yourself'),
            'topics.*.id.distinct' => '话题不能重复',
            'topics.between' => '话题必须在1到5个之间',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            'subject' => trans('plus-question::questions.attributes.subject'),
            'body' => trans('plus-question::questions.attributes.body'),
            'topics' => trans('plus-question::questions.attributes.topics'),
            'topics.*.id' => trans('plus-question::questions.attributes.topics'),
            'anonymity' => trans('plus-question::questions.attributes.anonymity'),
            'look' => trans('plus-question::questions.attributes.look'),
            'invitations.*.user' => trans('plus-question::questions.attributes.user'),
        ];
    }
}
