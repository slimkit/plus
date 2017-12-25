<?php

namespace SlimKit\PlusQuestion\API2\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PublishQuestion extends FormRequest
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
            'subject' => ['bail', 'required', 'max:255', 'regex:/[?|？]$/is'],
            'body' => 'nullable|string',
            'anonymity' => 'nullable|integer|in:0,1',
            'amount' => 'nullable|integer|max:'.$this->user()->wallet->balance,
            'look' => 'nullable|integer|in:0,1',
            'topics' => 'bail|required|array',
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
