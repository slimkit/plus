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

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\AdminControllers;

use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Cdn\UrlManager as CdnUrlManager;
use Zhiyi\Plus\Models\FileWith as FileWithModel;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\view;
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\MusicSinger;
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\MusicSinger as MusicSingerModel;
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Requests\SingerAdd as SingerAddRequest;
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Requests\SingerUpdate as SingerUpdateRequest;

class SingerController extends Controller
{
    /**
     * 歌手列表.
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function list(Request $request, MusicSinger $musicSinger)
    {
        $name = $request->query('name', '');
        $limit = $request->query('limit', 20);
        $type = $request->query('type', 'all');

        $singers = $this->search($musicSinger, $type, $name, $limit);

        return view('singers', [
            'base_url' => route('music:singers'),
            'name' => $name,
            'limit' => $limit,
            'singers' => $singers->items(),
            'page' => $singers->links(),
            'type' => $type,
        ]);
    }

    protected function search(MusicSinger $musicSinger, String $type, $name, $limit)
    {
        if ($type === 'all') {
            return $musicSinger->when($name, function ($query) use ($name) {
                return $query->where('name', 'like', "%{$name}%");
            })
            ->withTrashed()
            ->with('cover')
            ->orderBy('id', 'desc')
            ->paginate($limit);
        }

        if ($type === 'on') {
            return $musicSinger->when($name, function ($query) use ($name) {
                return $query->where('name', 'like', "%{$name}%");
            })
            ->with('cover')
            ->orderBy('id', 'desc')
            ->paginate($limit);
        }

        if ($type === 'down') {
            return $musicSinger->when($name, function ($query) use ($name) {
                return $query->where('name', 'like', "%{$name}%");
            })
            ->onlyTrashed()
            ->with('cover')
            ->orderBy('id', 'desc')
            ->paginate($limit);
        }
    }

    /**
     * 恢复被禁用的用户.
     * @param  MusicSingerModel $singer [description]
     * @return [type]                   [description]
     */
    public function restore(int $singer, MusicSingerModel $singerModel)
    {
        if ($singerModel->withTrashed()
            ->find($singer)
            ->restore()
        ) {
            return back()->with(['success-message' => '恢复成功']);
        }

        return back()->withError(['error-message' => '操作失败']);
    }

    /**
     * 格式化歌手封面图.
     * @param  CdnUrlManager $cdn      [description]
     * @param  FileWithModel $fileWith [description]
     * @param  int|int   $width    [description]
     * @param  int|int   $height   [description]
     * @param  int|int   $quality  [description]
     * @return [type]                  [description]
     */
    public function showCover(CdnUrlManager $cdn, FileWithModel $fileWith, int $width = 200, int $height = 200, int $quality = 60)
    {
        $fileWith->load('file');
        $extra = array_filter([
            'width' => $width,
            'height' => $height,
        ]);

        $extra['quality'] = $quality;
        $url = $cdn->make($fileWith->file, $extra);

        return $url;
    }

    /**
     * 添加歌手页面.
     */
    public function add()
    {
        return view('singerAdd');
    }

    /**
     * 添加歌手.
     * @param  Request          $request [description]
     * @param  MusicSingerModel $singer  [description]
     * @return [type]                    [description]
     */
    public function store(SingerAddRequest $request, MusicSingerModel $singer)
    {
        $name = $request->input('name');
        $cover = $request->input('cover');

        $singer->name = $name;
        $singer->cover = $cover;

        if ($singer->save()) {
            return back()->with(['success-message' => '添加成功']);
        }

        return back()->withError('系统错误');
    }

    /**
     * 歌手详情，用于编辑歌手.
     * @param  MusicSingerModel $singer [description]
     * @return [type]                   [description]
     */
    public function detail(MusicSingerModel $singer, FileWithModel $fileWith, CdnUrlManager $cdn)
    {
        $cover = $fileWith->find($singer->cover);
        $cover->load('file');
        $singer->storage = $cover;
        $singer->storageUrl = $this->showCover($cdn, $cover);
        $singer->file = $cover->file;

        return view('singerDetail', [
            'singer' => $singer,
        ]);
    }

    /**
     * 禁用歌手.
     * @param  MusicSingerModel $singer [description]
     * @return [type]                   [description]
     */
    public function disabled(MusicSingerModel $singer)
    {
        $singer->delete();

        return back()->with(['success-message' => '已禁用']);
    }

    public function update(SingerUpdateRequest $request, MusicSingerModel $singer, FileWithModel $fileWithModel)
    {
        $name = $request->input('name');
        $cover = $request->input('cover');
        $fileWith = null;

        if ($cover && $cover !== $singer->cover) {
            if ($fileWithModel->where('id', $cover)
                ->where('channel', null)
                ->where('raw', null)
                ->count()
            ) {
                $fileWithModel
                    ->where('channel', 'music:singer:cover')
                    ->where('raw', $singer->id)
                    ->forceDelete();
                $singer->cover = $cover;
                $fileWith = $this->updateFileWith($request);
            }
        }

        $singer->name = $name;

        try {
            $singer->saveOrFail();
            $singer->getConnection()->transaction(function () use ($singer, $fileWith) {
                $fileWith && $this->saveFileWith($fileWith, $singer);
            });
        } catch (\Exception $e) {
            throw $e;
        }

        return back()->with(['success-message' => '更新歌手成功']);
    }

    /**
     * 添加歌手设置封面.
     * @param  SingerAddRequest $request [description]
     * @return [type]                     [description]
     */
    protected function makeFileWith(SingerAddRequest $request)
    {
        return FileWithModel::where(
            'id',
            $request->input('cover')
        )->where('channel', null)
        ->where('raw', null)
        ->where('user_id', $request->user()->id)
        ->first();
    }

    /**
     * 保存需要更新的歌手设置封面.
     * @param  SingerAddRequest $request [description]
     * @return [type]                     [description]
     */
    protected function updateFileWith(SingerUpdateRequest $request)
    {
        return FileWithModel::where(
            'id',
            $request->input('cover')
        )->where('channel', null)
        ->where('raw', null)
        ->where('user_id', $request->user()->id)
        ->first();
    }

    /**
     * 保存专辑封面.
     * @param  [type]       $fileWith [description]
     * @param  MusicSingerModel $singer  [description]
     * @return [type]                 [description]
     */
    protected function saveFileWith($fileWith, MusicSingerModel $singer)
    {
        $fileWith->channel = 'music:singer:cover';
        $fileWith->raw = $singer->id;
        $fileWith->save();
    }
}
