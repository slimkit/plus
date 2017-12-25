<?php

namespace Zhiyi\PlusGroup\API\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CreateGroupPostRequest extends FormRequest
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
        $rules = [
            'title' => 'required',
            'body' => 'required',
            'summary' => 'present',
            'images' => 'required_if:summary,""|array',
        ];

        if ($this->syncFeed() === 1) {
            $rules = array_merge($rules, [
                'feed_from' => 'required|numeric|in:1,2,3,4,5',
            ]);
        }

        return $rules;
    }

    public function message(): array
    {
        $message = [
            'title.required' => '帖子标题不能为空',
            'body.required' => '帖子内容不能为空',
        ];

        if ($this->syncFeed() === 1) {
            $message = array_merge($message, [
                'feed_from.required' => '没有发送设备标识',
                'feed_from.in' => '设备标识不在允许范围',
            ]);
        }

        return $message;

    }

    /**
     * get sync feed status.
     *
     * @return int
     */
    protected function syncFeed(): int
    {
        return (int) $this->input('sync_feed');
    }
}
