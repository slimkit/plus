<?php

namespace Zhiyi\Plus\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Support\Configuration as ConfigurationRepository;

class CdnController extends Controller
{
    /**
     * Get selected cdn.
     *
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getCdnSelected()
    {
        return response()->json(['seleced' => config('cdn.default')], 200);
    }

    /**
     * Setting local CDN.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Zhiyi\Plus\Support\Configuration $repository
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function setCdnSeleced(Request $request, ConfigurationRepository $repository)
    {
        $repository->set(
            $this->makeBase($request)
        );

        return response()->json(['message' => '修改成功'], 201);
    }

    /**
     * Get qiniu setting.
     *
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function qiniu()
    {
        return response()->json([
            'domain' => config('cdn.generators.qiniu.domain'),
            'sign' => (bool) config('cdn.generators.qiniu.sign'),
            'expires' => (int) config('cdn.generators.qiniu.expires'),
            'ak' => config('cdn.generators.qiniu.ak'),
            'sk' => config('cdn.generators.qiniu.sk'),
        ], 200);
    }

    /**
     * Qiniu setting.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Zhiyi\Plus\Support\Configuration $repository
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function setQiniu(Request $request, ConfigurationRepository $repository)
    {
        $repository->set(array_merge($this->makeBase($request), [
            'cdn.generators.qiniu.domain' => $request->input('domain'),
            'cdn.generators.qiniu.sign' => (bool) $request->input('sign'),
            'cdn.generators.qiniu.expires' => (int) $request->inut('expires'),
            'cdn.generators.qiniu.ak' => $request->input('ak'),
            'cdn.generators.qiniu.sk' => $request->input('sk'),
        ]));

        return response()->json(['message' => '设置成功'], 200);
    }

    /**
     * Make base settings.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function makeBase(Request $request): array
    {
        $seleced = $request->input('cdn');

        if (! $seleced) {
            return [];
        }

        if (! in_array($seleced, array_keys(config('cdn.generators')))) {
            return response()->json(['message' => '选择的 CDN 是不被支持的'], 422);
        }

        return [
            'cdn.default' => $seleced,
        ];
    }
}
