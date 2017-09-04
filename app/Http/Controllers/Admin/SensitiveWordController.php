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
     * @return array json
     */
    public function index(Request $request)
    {
        $perPage = $request->get('perPage');
        $keyword = $request->get('keyword');

        $items = SensitiveWord::with(['user', 'filterWordCategory', 'filterWordType'])
                 ->when($keyword, function ($query) use ($keyword) {
                     $query->where('name', 'like', sprintf('%%%s%%', $keyword));
                 })
                 ->paginate($perPage);

        return response($items);
    }

    /**
     * 获取敏感词.
     *
     * @param  SensitiveWord $sensitiveWord
     * @return array json
     */
    public function show(SensitiveWord $word)
    {
        return response($word, 200);
    }

    /**
     * 创建敏感词.
     *
     * @param Request $request
     * @return array json
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->rule(), $this->msg());

        $data = $request->all();
        $data = array_merge($data, ['user_id' => Auth::user()->id]);

        SensitiveWord::create($data);

        return response(['message' => ['添加敏感词成功']], 201);
    }

    /**
     * 更新敏感词.
     *
     * @param  Request $request
     * @param  SensitiveWord $word
     * @return array json
     */
    public function update(Request $request, SensitiveWord $word)
    {
        $this->validate($request, $this->rule(), $this->msg());

        $data = $request->only('name', 'filter_word_category_id', 'filter_word_type_id');

        $word->update($data);

        return response(['message' => ['更新成功']], 201);
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

    /**
     * 删除敏感词.
     *
     * @param SensitiveWord $sensitiveWord
     * @return array json
     */
    public function delete(SensitiveWord $word)
    {
        $sensitiveWord->delete();

        return response('', 204);
    }
}
