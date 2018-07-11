<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2018 Chengdu ZhiYiChuangXiang Technology Co., Ltd.     |
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

namespace SlimKit\PlusAroundAmap\API\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Zhiyi\Plus\Support\Configuration;
use GuzzleHttp\Client as GuzzleHttpClient;
use Illuminate\Contracts\Routing\ResponseFactory;
use SlimKit\PlusAroundAmap\Models\AroundAmap as AroundAmapModel;

class HomeController extends Controller
{
    // 高德自定义地图创建数据接口
    protected $_create_uri = 'http://yuntuapi.amap.com/datamanage/data/create';

    // 高德自定义地图更新数据接口
    protected $_update_uri = 'http://yuntuapi.amap.com/datamanage/data/update';

    // 高德自定义地图删除数据接口
    protected $_delete_uri = 'http://yuntuapi.amap.com/datamanage/data/delete';

    // 高德自定义地图查询数据接口
    protected $_search_uri = 'http://yuntuapi.amap.com/datasearch/around?';

    protected $_getgeo_uri = 'http://restapi.amap.com/v3/geocode/geo?';

    // 高德应用的KEY, 会由后台提供
    protected $_amap_key;

    // 高德应用的密钥, 需要由后台配置提供;
    protected $_amap_sig;

    // 高德自定义地图ID 需要由后端提供
    protected $_amap_tableId;

    protected $http;

    protected $errors = [
        'INVALID_USER_KEY' => 'KEY无效',
        'SERVICE_NOT_EXIST' => '请求的服务不存在',
        'USER_VISIT_TOO_FREQUENTLY' => '请求过于频繁',
        'INVALID_USER_SIGNATURE' => '数字签名错误',
        'INVALID_USER_SCode' => '用户安全码未通过',
        'SERVICE_NOT_AVAILABLE' => '没有使用该接口的权限',
        'DAILY_QUERY_OVER_LIMIT' => '访问已超出日访问量',
        'ACCESS_TOO_FREQUENT' => '单位时间内访问过于频繁',
        'USERKEY_PLAT_NOMATCH' => '请求key与绑定平台不符',
        'IP_QUERY_OVER_LIMIT' => 'IP访问超限',
        'INSUFFICIENT_PRIVILEGES' => '权限不足，服务请求被拒绝',
        'QPS_HAS_EXCEEDED_THE_LIMIT' => '云图服务QPS超限',
        'INVALID_PARAMS' => '请求参数非法',
        'MISSING_REQUIRED_PARAMS' => '缺少必填参数',
        'UNKNOWN_ERROR' => '未知错误',
        'ENGINE_RESPONSE_DATA_ERROR' => '服务响应失败',
    ];

    public function __construct(Configuration $config)
    {
        $this->http = new GuzzleHttpClient();
        $conf = $config->getConfigurationBase();
        $this->_amap_sig = array_get($conf, 'around-amap.amap-sig') ?? '';
        $this->_amap_key = array_get($conf, 'around-amap.amap-key') ?? '';
        $this->_amap_tableId = array_get($conf, 'around-amap.amap-tableid') ?? '';
    }

    // 数据总线
    public function index(Request $request, AroundAmapModel $around, ResponseFactory $response)
    {
        $user = $request->user();
        $aroundAmap = $around->find($user->id);
        if (! $aroundAmap) {
            return $this->create($request, $around, $response);
        } else {
            return $this->update($request, $response, $aroundAmap);
        }
    }

    /**
     * 创建当前用户在高德地图中的自定义位置.
     */
    public function create(Request $request, AroundAmapModel $around, ResponseFactory $response)
    {
        $user = $request->user();
        $latitude = $request->input('latitude', '');
        $longitude = $request->input('longitude', '');
        if (! $latitude) {
            return $response->json(['message' => '请传递GPS纬度坐标'], 400);
        }
        if (! $longitude) {
            return $response->json(['message' => '请传递GPS经度坐标'], 400);
        }
        $around->user_id = $user->id;
        $around->longitude = $longitude;
        $around->latitude = $latitude;
        $_location = $longitude.','.$latitude;
        $localtype = 1; // 采用经纬度方式
        $data = json_encode([
            '_location' => $_location,
            '_name' => $user->name,
            'user_id' => $user->id,
        ]);
        $prams = [
            'data' => $data,
            'key' => $this->_amap_key,
            'localtype' => $localtype,
            'tableid' => $this->_amap_tableId,
        ];
        $sig = md5(urldecode(http_build_query($prams, '', '&')).$this->_amap_sig);
        $prams['sig'] = $sig;
        $result = json_decode($this->http->post($this->_create_uri, [
                'form_params' => $prams,
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ],
            ])
            ->getBody()
            ->getContents(), true);

        if ($result['status'] === 1) {
            $around->_id = $result['_id'];
            $around->save();

            return $response->json(['message' => '位置创建成功', '_id' => $result['_id']])->setStatusCode(201);
        } else {
            return $response->json(['message' => $this->errors[$result['info']] ?? '未知错误'], 500);
        }
    }

    /**
     * 更新当前用户在高德自定义地图中的位置.
     */
    public function update(Request $request, ResponseFactory $response, AroundAmapModel $around)
    {
        $user = $request->user();
        $latitude = $request->input('latitude', '');
        $longitude = $request->input('longitude', '');
        $aroundAmap = $around->find($user->id);
        $_id = $aroundAmap['_id'];
        if (! $_id) {
            return $response->json(['message' => '系统错误, 请联系管理员'], 500);
        }

        if (! $latitude) {
            return $response->json(['message' => '纬度坐标获取失败'], 400);
        }
        if (! $longitude) {
            return $response->json(['message' => '经度坐标获取失败'], 400);
        }
        $_location = $longitude.','.$latitude;
        $data = json_encode([
            '_id' => $_id,
            '_location' => $_location,
            '_name' => $user->name,

        ]);
        $prams = [
            'data' => $data,
            'key' => $this->_amap_key,
            'tableid' => $this->_amap_tableId,
        ];
        $sig = md5(urldecode(http_build_query($prams, '', '&')).$this->_amap_sig);
        $prams['sig'] = $sig;
        $result = json_decode($this->http->post($this->_update_uri, [
                'form_params' => $prams,
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ],
            ])
            ->getBody()
            ->getContents(), true);
        if ($result['status'] === 1) {
            $aroundAmap->longitude = $longitude;
            $aroundAmap->latitude = $latitude;
            $aroundAmap->save();

            return $response->json(['message' => '位置更新成功'])->setStatusCode(201);
        } else {
            return $response->json(['message' => $this->errors[$result['info']] ?? '未知错误'], 500);
        }
    }

    /**
     * 清除当前用户在高德自定义地图中的位置.
     */
    public function delete(Request $request, ResponseFactory $response, AroundAmapModel $around)
    {
        $user = $request->user();
        $aroundAmap = $around->find($user->id);
        $_id = $aroundAmap['_id'];
        $parmas = [
        'ids' => $_id,
        'key' => $this->_amap_key,
        'tableid' => $this->_amap_tableId,
        ];
        $sig = md5(urldecode(http_build_query($parmas, '', '&')).$this->_amap_sig);
        $parmas['sig'] = $sig;
        $result = json_decode($this->http->post($this->_delete_uri, [
        'form_params' => $parmas,
        'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
        ])
        ->getBody()
        ->getContents(), true);

        if ($result['status'] && ! $result['fail']) {
            $aroundAmap->delete();

            return $response->json()->setStatusCode(204);
        } else {
            return $response->json(['message' => $this->errors[$result['info']] ?? '未知错误'], 500);
        }
    }

    /**
     * 获取附近的人.
     */
    public function getArounds(Request $request, ResponseFactory $response)
    {
        $user = $request->user('api')->id ?? 0;
        $latitude = $request->input('latitude', '');
        $longitude = $request->input('longitude', '');
        if (! $latitude) {
            return $response->json(['message' => '纬度坐标获取失败'], 400);
        }
        if (! $longitude) {
            $response->json(['message' => '经度坐标获取失败'], 400);
        }
        $center = $longitude.','.$latitude;
        // 查询半径
        $radius = $request->input('radius', 3000); // 默认3km范围的用户, 最大为50km
        $page = $request->input('page', 1);
        $limit = $request->input('limit', ($page === 1 && $user) ? 16 : 15); // 默认15条数据
        $searchtype = 0; // 搜索半径代表类型 默认为0， 直线距离
        // 组装参数
        $prams = "center={$center}&key={$this->_amap_key}&limit={$limit}&page={$page}&radius={$radius}&searchtype={$searchtype}&tableid={$this->_amap_tableId}";
        // 计算数字签名
        $sig = md5($prams.$this->_amap_sig);
        $uri = $prams."&sig={$sig}";
        $results = json_decode($this->http->get($this->_search_uri.$uri)->getBody()->getContents());
        if ($results->status) {
            $datas = $results->datas;
            if ($user) {
                foreach ($datas as $key => $data) {
                    if ($data->user_id === $user) {
                        unset($datas[$key]);
                    }
                }
                $datas = collect(array_values($datas));
            }

            return $response->json($datas)->setStatusCode(200);
        } else {
            return $response->json(['message' => $this->errors[$results->info] ?? '未知错误'], 500);
        }
    }

    /**
     * 地址换取经纬度.
     */
    public function getGeo(Request $request, ResponseFactory $response)
    {
        $address = urlencode($request->input('address', ''));
        if (! $address) {
            $response->json(['message' => '请输入要获取的地址'], 400);
        }
        $address = urldecode($address);
        $parmas = "address={$address}&key={$this->_amap_key}";
        $sig = md5($parmas.$this->_amap_sig);
        $parmas .= '&sig='.$sig;
        $results = json_decode($this->http->get($this->_getgeo_uri.$parmas)->getBody()->getContents());
        if ($results->status) {
            return response()->json($results)->setStatusCode(200);
        }

        return $response->json(['message' => $this->errors[$results->info] ?? '未知错误'], 500);
    }
}
