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

class UpdateQuestion extends FormRequest
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
            'subject' => ['required_without_all:body,anonymity,topics,amount', 'nullable', 'string', 'max:255', 'regex:/[?|？]$/is'],
            'body' => 'required_without_all:subject,anonymity,topics,amount|nullable|string',
            'anonymity' => 'required_without_all:subject,body,topics,amount|nullable',
            'topics' => 'required_without_all:subject,body,anonymity,amount|nullable|array',
            'topics.*.id' => 'required_with:topics|distinct|exists:topics,id',
            'amount' => 'required_without_all:subject,body,anonymity,topics|int',
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
            'anonymity' => trans('plus-question::questions.attributes.anonymity'),
            'topics' => trans('plus-question::questions.attributes.topics'),
            'topics.*.id' => trans('plus-question::questions.attributes.topics'),
            'amount' => trans('plus-question::questions.attributes.amount'),
        ];
    }
}
