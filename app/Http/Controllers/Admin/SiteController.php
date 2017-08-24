<?php

namespace Zhiyi\Plus\Http\Controllers\Admin;

use Carbon\Carbon;
use Zhiyi\Plus\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Zhiyi\Plus\Models\CommonConfig;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Support\Facades\Cache;
use Zhiyi\Plus\Support\Configuration;
use Illuminate\Contracts\Config\Repository;
use Zhiyi\Plus\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;

class SiteController extends Controller
{
    /**
     * The store CommonConfig instance.
     *
     * @var Zhiyi\Plus\Models\CommonConfig
     */
    protected $commonCinfigModel;

    protected $app;

    /**
     * Construct handle.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function __construct(Application $app, CommonConfig $config)
    {
        $this->app = $app;
        $this->commonCinfigModel = $config;
    }

    /**
     * Get the website info.
     *
     * @return mixed
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function get(Request $request, Repository $config, ResponseFactory $response)
    {
        if (! $request->user()->ability('admin:site:base')) {
            return response()->json([
                'message' => '没有权限查看该项信息',
            ])->setStatusCode(403);
        }

        $name = $config->get('app.name', 'ThinkSNS+');
        $keywords = $config->get('app.keywords');
        $description = $config->get('app.description');
        $icp = $config->get('app.icp');

        return $response->json([
            'name' => $name,
            'keywords' => $keywords,
            'description' => $description,
            'icp' => $icp,
        ])->setStatusCode(200);
    }

    /**
     * 更新网站基本信息.
     *
     * @param Request $request
     *
     * @return mixed
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function updateSiteInfo(Request $request, Configuration $config, ResponseFactory $response)
    {
        if (! $request->user()->ability('admin:site:base')) {
            return response()->json([
                'message' => '没有权限更新该信息',
            ])->setStatusCode(403);
        }

        $keys = ['name', 'keywords', 'description', 'icp'];
        // $requestSites = array_filter($request->only($keys));

        $site = [];
        foreach ($request->only($keys) as $key => $value) {
            $site['app.'.$key] = $value;
        }
        $config->set($site);

        return $response->json([
            'message' => '更新成功',
        ])->setStatusCode(201);
    }

    /**
     * Get all areas.
     *
     * @return mixed
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function areas(Request $request)
    {
        if (! $request->user()->ability('admin:area:show')) {
            return response()->json([
                'message' => '你没有权限查看地区数据',
            ])->setStatusCode(403);
        }

        $expiresAt = Carbon::now()->addMonth(12);
        $areas = Cache::remember('areas', $expiresAt, function () {
            return Area::all();
        });

        return response()->json($areas ?: [])->setStatusCode(200);
    }

    /**
     * 添加地区.
     *
     * @param Request $request
     *
     * @return mixed [description]
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function doAddArea(Request $request)
    {
        if (! $request->user()->ability('admin:area:add')) {
            return response()->json([
                'error' => ['你没有添加地区权限'],
            ])->setStatusCode(403);
        }

        $name = $request->input('name');
        $extends = $request->input('extends', '');
        $pid = $request->input('pid', 0);

        if (! $name) {
            return response()->json([
                'error' => ['name' => '名称不能为空'],
            ])->setStatusCode(422);
        } elseif ($pid && ! Area::find($pid)) {
            return response()->json([
                'error' => ['pid' => '父地区不存在'],
            ])->setStatusCode(422);
        }

        $area = new Area();
        $area->name = $name;
        $area->extends = $extends;
        $area->pid = $pid;
        if (! $area->save()) {
            return response()->json([
                'error' => ['数据库保存失败'],
            ])->setStatusCode(500);
        }

        Cache::forget('areas');

        return response()->json($area)->setStatusCode(201);
    }

    /**
     * 删除地区.
     *
     * @param int $id
     *
     * @return mixed
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function deleteArea(Request $request, int $id)
    {
        if (! $request->user()->ability('admin:area:delete')) {
            return response()->json([
                'error' => ['你没有权限删除地区'],
            ])->setStatusCode(403);
        }

        $notEmpty = Area::byPid($id)->first();
        if ($notEmpty) {
            return response()->json([
                'error' => '请先删除该地区下级地区',
            ])->setStatusCode(422);
        }

        Area::where('id', $id)->delete();
        Cache::forget('areas');

        return response('', 204);
    }

    /**
     * 更新地区数据.
     *
     * @param Request $request
     * @param Area    $area
     *
     * @return mixed
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function patchArea(Request $request, Area $area)
    {
        if (! $request->user()->ability('admin:area:update')) {
            return response()->json([
                'error' => ['你没有更新地区权限'],
            ])->setStatusCode(403);
        }

        $key = $request->input('key');
        $value = $request->input('value', '');

        if (! in_array($key, ['name', 'extends'])) {
            return response()->json([
                'error' => ['请求不合法'],
            ])->setStatusCode(422);
        } elseif ($key == 'name' && ! $value) {
            return response()->json([
                'error' => ['name' => '地区名称不能为空'],
            ])->setStatusCode(422);
        }

        $area->$key = $value;
        if (! $area->save()) {
            return response()->json([
                'error' => ['数据更新失败'],
            ])->setStatusCode(500);
        }

        Cache::forget('areas');

        return response()->json([
            'message' => [$key => '更新成功'],
        ])->setStatusCode(201);
    }

    /**
     * 获取热门地区数据.
     *
     * @return mixed
     */
    public function hots(ResponseFactory $response)
    {
        $hots = CommonConfig::byNamespace('common')
            ->byName('hots_area')
            ->value('value');

        $toHot = $hots ? json_decode($hots) : [];

        return $response->json([
            'data' => $toHot,
        ])->setStatusCode(200);
    }

    /**
     * 添加、更新 热门地区.
     *
     * @return mixed
     */
    public function doHots(Request $request, ResponseFactory $response)
    {
        $update = $request->input('update');
        $areaStr = $request->input('content');

        if (count(explode(' ', $areaStr)) < 2) {
            return $response->json(['error' => ['地区不能小于两级']], 422);
        }

        $hots = collect(
            json_decode($this->commonCinfigModel->byNamespace('common')
                ->byName('hots_area')
                ->value('value'), true) ?: []
        );

        $map = $hots->when(! in_array($areaStr, $hots->all()) && ! $update, function ($map) use ($areaStr) {
            $map->push($areaStr);

            return $map;
        })->map(function (string $str) use ($areaStr, $update) {
            if ($update && $str === $areaStr) {
                return null;
            }

            return $str;
        })->all();

        $this->commonCinfigModel->updateOrCreate(
            ['namespace' => 'common', 'name' => 'hots_area'],
            ['value' => json_encode(array_filter($map))]
        );

        return $response->json([
            'message' => '操作成功',
            'status' => $update ? 2 : 1,
        ])->setStatusCode(201);
    }

    /**
     * Get mail configuration information.
     *
     * @return mixed
     */
    public function mail(Request $request, Repository $config, ResponseFactory $response)
    {
        // if (! $request->user()->ability('admin:mail:show')) {
        //     return response()->json([
        //         'message' => '没有权限查看该项信息',
        //     ])->setStatusCode(403);
        // }

        $driver = $config->get('mail.driver', 'smtp');
        $host = $config->get('mail.host');
        $port = $config->get('mail.port');
        $from = $config->get('mail.from');
        $encryption = $config->get('mail.encryption');
        $username = $config->get('mail.username');
        $password = $config->get('mail.password');

        return $response->json([
            'driver' => $driver,
            'host' => $host,
            'port' => $port,
            'from' => $from,
            'encryption' => $encryption,
            'username' => $username,
            'password' => $password,
        ])->setStatusCode(200);
    }

    /**
     * Update the mail configuration information.
     *
     * @return mixed
     */
    public function updateMailInfo(Request $request, Configuration $config, ResponseFactory $response)
    {
        // if (! $request->user()->ability('admin:mail:show')) {
        //     return response()->json([
        //         'message' => '没有权限更新该信息',
        //     ])->setStatusCode(403);
        // }

        $site = [];
        foreach ($request->all() as $key => $value) {
            $site['mail.'.$key] = $value;
        }
        $config->set($site);

        return $response->json([
            'message' => '更新成功',
        ])->setStatusCode(201);
    }

    /**
     * 测试发送邮件.
     *
     * @return mixed
     */
    public function sendMail(Request $request, Mailer $mailer, ResponseFactory $response)
    {
        $title = '测试邮件';
        $email = $request->input('email');
        $content = $request->input('content');
        $mailer->raw($title, function ($message) use ($email, $content) {
            $message->subject($content);
            $message->to($email);
        });

        return $response->json([
            'message' => '发送成功',
        ])->setStatusCode(201);
    }

    /**
     * 服务器信息.
     */
    public function server(ResponseFactory $response)
    {
        $system = [
            'php_version' => PHP_VERSION,
            'os' => PHP_OS,
            'server' => $_SERVER['SERVER_SOFTWARE'],
            'db' => env('DB_CONNECTION'),
            'port' => $_SERVER['SERVER_PORT'],
            'root' => $_SERVER['DOCUMENT_ROOT'],
            'agent' => $_SERVER['HTTP_USER_AGENT'],
            'protocol' => $_SERVER['SERVER_PROTOCOL'],
            'method' => $_SERVER['REQUEST_METHOD'],
            'laravel_version' => app()::VERSION,
            'max_upload_size' => ini_get('upload_max_filesize'),
            'execute_time' => ini_get('max_execution_time').'秒',
            'server_date' => date('Y年n月j日 H:i:s'),
            'local_date' => gmdate('Y年n月j日 H:i:s', time() + 8 * 3600),
            'domain_ip' => $_SERVER['SERVER_NAME'].' [ '.$_SERVER['SERVER_ADDR'].' ]',
            'user_ip' => $_SERVER['REMOTE_ADDR'],
            'disk' => round((disk_free_space('.') / (1024 * 1024)), 2).'M',
        ];

        return $response->json($system)->setStatusCode(200);
    }
}
