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

class NewUpdateQuestion extends FormRequest
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
            'subject' => ['required_without_all:body,anonymity,topics,amount', 'nullable', 'string', 'min:2', 'max:50', 'regex:/[?|？]$/is'],
            'body' => 'required_without_all:subject,anonymity,topics,amount|nullable|string',
            'anonymity' => 'required_without_all:subject,body,topics,amount|nullable',
            'topics' => 'required_without_all:subject,body,anonymity,amount|nullable|array|between:1,5',
            'topics.*.id' => 'required_with:topics|distinct|exists:topics,id',
            'amount' => 'required_without_all:subject,body,anonymity,topics|int|max:'.$currency->sum,
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
            'anonymity' => trans('plus-question::questions.attributes.anonymity'),
            'topics' => trans('plus-question::questions.attributes.topics'),
            'topics.*.id' => trans('plus-question::questions.attributes.topics'),
            'amount' => trans('plus-question::questions.attributes.amount'),
        ];
    }
}
