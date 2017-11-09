<?php

namespace Zhiyi\Plus\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Zhiyi\Plus\Models\Advertising;
use Zhiyi\Plus\Models\AdvertisingSpace;
use Zhiyi\Plus\Http\Controllers\Controller;

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
        $keyword = $request->get('keyword');
        $spaceId = (int) $request->get('space_id');
        $limit = (int) $request->get('limit', 15);
        $offset = (int) $request->get('offset', 0);

        $query = Advertising::with(['space' => function ($query) {
            $query->select('id', 'alias');
        }])
        ->when($spaceId, function ($query) use ($spaceId) {
            $query->where('space_id', $spaceId);
        })
        ->when($keyword, function ($query) use ($keyword) {
            $query->where('title', 'like', sprintf('%%%s%%', $keyword));
        });

        $total = $query->count('id');
        $items = $query->limit($limit)
        ->offset($offset)
        ->orderBy('id', 'desc')
        ->get();

        return response()->json($items, 200, ['x-ad-total' => $total]);
    }

    /**
     * 根据ID获取广告.
     *
     * @param  Advertising $ad
     * @return \Illuminate\Http\JsonResponse
     */
    public function showAd(Advertising $ad)
    {
        return response()->json($ad, 200);
    }

    /**
     * 创建广告.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeAd(Request $request)
    {
        $this->validate($request, $this->basisRule(), $this->basisMsg());

        $space = new AdvertisingSpace();
        $space = $space->find($request->input('space_id'));

        if (! $space) {
            return response()->json(['message' => ['所属广告位不存在']], 404);
        }

        $this->checkData($space, $request);

        $formData = $request->all();

        $model = new Advertising();

        $model->title = $formData['title'];
        $model->type = $formData['type'];
        $model->sort = $formData['sort'];
        $model->space_id = $formData['space_id'];

        $model->data = $this->formatData($space, $formData['data'], $formData['type']);

        if ($model->save()) {
            return response()->json([
                'message' => ['添加广告成功'],
                'ad_id' => $model->id,
            ], 201);
        } else {
            return response()->json(['message' => ['添加广告失败']], 500);
        }
    }

    /**
     * 更新广告.
     *
     * @param  Request $request
     * @param  Advertising $ad
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateAd(Request $request, Advertising $ad)
    {
        $this->validate($request, $this->basisRule(), $this->basisMsg());

        $space = new AdvertisingSpace();
        $space = $space->find($request->input('space_id'));

        if (! $space) {
            return response()->json(['message' => ['所属广告位不存在']], 404);
        }

        $this->checkData($space, $request);

        $formData = $request->all();

        $ad->title = $formData['title'];
        $ad->type = $formData['type'];
        $ad->sort = $formData['sort'];
        $ad->space_id = $formData['space_id'];

        $ad->data = $this->formatData($space, $formData['data'], $formData['type']);

        if ($ad->save()) {
            return response()->json(['message' => ['更新广告成功']], 201);
        } else {
            return response()->json(['message' => ['更新广告失败']], 500);
        }
    }

    private function byAdTypeGetData($type, array $data)
    {
        $items = [];

        switch ($type) {
            case 'image':
                $items['image'] = $data['image'];
                $items['link'] = $data['link'];
                $items['title'] = $data['title'] ?? '';
                $items['duration'] = (int) $data['duration'] ?? 3;
                break;
            case 'feed:analog':
                $items['avatar'] = $data['avatar'];
                $items['name'] = $data['name'];
                $items['content'] = $data['content'];
                $items['image'] = $data['image'];
                $items['time'] = Carbon::parse($data['time'])->toDateTimeString();
                $items['link'] = $data['link'];
                break;
            case 'news:analog':
                $items['title'] = $data['title'];
                $items['image'] = $data['image'];
                $items['from'] = $data['from'];
                $items['time'] = Carbon::parse($data['time'])->toDateTimeString();
                $items['link'] = $data['link'];
                break;
        }

        return $items;
    }

    private function basisRule()
    {
        return [
            'title' => 'required',
            'space_id' => 'required|numeric',
            'type' => 'required|string',
            'sort' => 'required|numeric',
        ];
    }

    private function basisMsg()
    {
        return [
            'title.required' => '广告标题必填',
            'type.required' => '广告类型必填',
            'type.string' => '广告类型格式错误',
            'space_id.required' => '广告位置必选',
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
                    'data.content' => 'required',
                    'data.avatar' => 'required|url',
                    'data.name' => 'required',
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
           'data.content.required' => '内容必填',
           'data.time.date' => '时间格式错误',
           'data.from.required' => '来源必填',
           'data.title.required' => '标题必填',
           'data.name.required' => '用户名必填',
        ];
    }

    /**
     * 验证广告详细数据.
     *
     * @param AdvertisingSpace $space
     * @param Request $request
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    protected function checkData(AdvertisingSpace $space, Request $request)
    {
        $rules = collect($space->rule[$request->input('type')])->mapWithKeys(function ($rule, $key) {
            $key = 'data.'.$key;

            return [$key => $rule];
        })->toArray();

        $messages = collect($space->message[$request->input('type')])->mapWithKeys(function ($message, $key) {
            $key = 'data.'.$key;

            return [$key => $message];
        })->toArray();

        $this->validate($request, $rules, $messages);
    }

    protected function formatData(AdvertisingSpace $space, array $data = [], string $type)
    {
        $format = collect($space->format[$type])->map(function ($value) {
            return explode('|', $value);
        });

        // 不传data时，只返回当前广告位格式
        if (empty($data)) {
            return $format->toArray();
        }

        return collect($data)->map(function ($value, $key) use ($format) {
            if ($format->has($key)) {
                switch ($format->get($key)[1]) {
                    case 'string':
                        $value = (string) $value;
                        break;
                    case 'integer':
                        $value = (int) $value;
                        break;
                    case 'int':
                        $value = (int) $value;
                        break;
                    case 'date':
                        $value = Carbon::parse($value)->toDateTimeString();
                        break;
                }

                return $value;
            }
        })->filter();
    }

    /**
     * 获取广告位列表.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function spaces()
    {
        $items = AdvertisingSpace::select(['id', 'space', 'alias', 'format', 'allow_type'])->get();

        return response()->json($items, 200);
    }

    /**
     * 删除广告.
     *
     * @param  Advertising $ad
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteAd(Advertising $ad)
    {
        if ($ad->delete()) {
            return response('', 204);
        } else {
            return response()->json(['message' => ['删除广告失败']], 500);
        }
    }
}
