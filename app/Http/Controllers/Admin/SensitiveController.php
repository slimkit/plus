<?php

namespace Zhiyi\Plus\Http\Controllers\Admin;

use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Models\Sensitive as SensitiveModel;
use Zhiyi\Plus\Http\Requests\Admin\CreateSensitive as CreateSensitiveRequest;

class SensitiveController extends Controller
{
    /**
     * Create a sensitive.
     *
     * @param \Zhiyi\Plus\Http\Requests\Admin\CreateSensitive $request
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function store(CreateSensitiveRequest $request)
    {
        $word = $request->input('word');
        $type = $request->input('type');
        $replace = $request->input('replace');

        $sensitive = new SensitiveModel();
        $sensitive->word = $word;
        $sensitive->type = $type;
        $sensitive->replace = $type === 'replace' ? $replace : null;
        $sensitive->save();

        return response(['message' => '添加成功！', 'sensitive' => $sensitive], 201);
    }
}
