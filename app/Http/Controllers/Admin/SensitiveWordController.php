<?php

namespace Zhiyi\Plus\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Zhiyi\Plus\Models\SensitiveWord;
use Zhiyi\Plus\Http\Controllers\Controller;

class SensitiveWordController extends Controller
{
    /**
     * 敏感词列表.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $perPage = $request->get('perPage');

        $items = SensitiveWord::with(['user', 'filterWordCategory', 'filterWordType'])
                 ->paginate($perPage);

        return response()->json($items, 200);
    }

    /**
     * 创建敏感词.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->rule(), $this->msg());

        $data = $request->all();
        $data = array_merge($data, ['user_id' => Auth::user()->id]);

        SensitiveWord::create($data);

        return response()->json(['message' => ['添加敏感词成功']], 201);
    }

    /**
     * 验证规则.
     *
     * @return array
     */
    private function rule()
    {
        $rule = [
            'name' => 'required',
            'filter_word_category_id' => 'required|exists:filter_word_categories,id',
            'filter_word_type_id' => 'required|exists:filter_word_types,id',
        ];

        return $rule;
    }

    /**
     * 错误提示.
     *
     * @return array
     */
    private function msg()
    {
        $msg = [
            'name.required' => '名称必须填写',
            'filter_word_category_id.required' => '类型必须选择',
            'filter_word_category_id.exists' => '类型不存在',
            'filter_word_type_id.required' => '类别必须选择',
            'filter_word_type_id.exists' => '类别不存在',
        ];

        return $msg;
    }
}
