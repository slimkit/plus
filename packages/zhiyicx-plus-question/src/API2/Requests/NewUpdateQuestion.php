<?php

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
            'subject' => ['required_without_all:body,anonymity,topics,amount', 'nullable', 'string', 'max:255', 'regex:/[?|？]$/is'],
            'body' => 'required_without_all:subject,anonymity,topics,amount|nullable|string',
            'anonymity' => 'required_without_all:subject,body,topics,amount|nullable',
            'topics' => 'required_without_all:subject,body,anonymity,amount|nullable|array',
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
