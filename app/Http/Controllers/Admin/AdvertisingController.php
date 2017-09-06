<?php

namespace Zhiyi\Plus\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Models\Advertising;
use Zhiyi\Plus\Models\AdvertisingSpace;

class AdvertisingController extends Controller
{

    /**
     * 获取广告.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function ads(Request $request)
    {
        $perPage = $request->get('perPage');

        $items = Advertising::with('space')
            ->paginate($perPage);

        return response()->json($items, 200);
    }

    /**
     * 创建广告.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeAds(Request $request)
    {
        $this->validate($request, $this->basisRule(), $this->basisMsg());

        $this->validate($request, $this->dataRule(), $this->dataMsg());

        $formData = $request->all();

        $model = new Advertising();

        $model->title = $formData['title'];
        $model->type  = $formData['type'];
        $model->sort  = $formData['sort'];
        $model->space_id = $formData['space_id'];

        $data = $formData['data'];
        
        $items = [];

        switch ($formData['type']) {
            case 'image':
                $items['image'] = $data['image'];
                $items['link']  = $data['link'];
                break;
            case 'feed:analog':
                $items['avatar'] = $data['avatar'];
                $items['name']  = $data['name'];
                $items['content'] = $data['content'];
                $items['image']  = $data['image'];
                $items['time'] = $data['time'];
                $items['link']  = $data['link'];
                break;
            case 'news:analog':
                $items['title'] = $data['title'];
                $items['image']  = $data['image'];
                $items['from'] = $data['from'];
                $items['time']  = $data['time'];
                $items['link'] = $data['link'];
                break;
        }

        $model->data = $items;

        if ($model->save()) {
            return response()->json(['message' => ['添加广告成功']], 201);
        } else {
            return response()->json(['message' => ['添加广告失败']], 422);
        }
    }

    private function basisRule()
    {
        return [
            'title' => 'required',
            'type' => 'required|string',
            'space_id' => 'required|numeric',
            'sort' => 'required|numeric',
        ];
    }

    private function basisMsg()
    {
        return [
            'title.required' => '广告标题必填',
            'type.required' => '广告类型必填',
            'type.string' => '广告类型格式错误',
            'space_id.required' => '广告位必选',
            'space_id.numeric' => '广告位格式错误',
            'sort.required' => '广告排序必填',
            'sort.numeric' => '广告排序类型格式错误',
        ];
    }

    private function dataRule()
    {
        $rule = [];

        switch (request()->get('type')) {
            case 'image':
                $rule = [
                    'data.image' => 'required|url',
                    'data.link'  => 'required|url',
                ];
                break;
            case 'feed:analog':
                $rule = [
                    'data.image' => 'required|url',
                    'data.link'  => 'required|url',
                    'data.time' => 'required|date',
                    'data.avatar' => 'required|url',
                    'data.title' => 'required',
                    'data.name' => 'required'
                ];
                break;
            case 'news:analog':
                $rule = [
                    'data.image' => 'required|url',
                    'data.link'  => 'required|url',
                    'data.time' => 'required|date',
                    'data.title' => 'required',
                    'data.from' => 'required',
                ];
                break;
        }

        return $rule;
    }

    private function dataMsg()
    {
        return [
           'data.image.required' => '广告图链接不能为空',
           'data.image.url' => '广告图链接无效',
           'data.link.required' => '广告链接不能为空',
           'data.link.url' => '广告链接无效',
           'data.avatar.required' => '头像图链接必填',
           'data.avatar.url' => '头像图链接无效',
           'data.time.required' => '时间必填',
           'data.time.date' => '时间格式错误',
           'data.from.required' => '来源必填',
           'data.title.required' => '标题必填',
           'data.name.required' => '用户名必填',
        ];
    }

    /**
     * 获取广告位列表.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function spaces()
    {
        $items = AdvertisingSpace::select(['id', 'space','alias', 'format', 'allow_type'])->get();

        return response()->json($items, 200);
    }
}
