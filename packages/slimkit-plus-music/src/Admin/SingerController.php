<?php

declare(strict_types=1);

/*
 * +----------------------------------------------------------------------+
 * |                          ThinkSNS Plus                               |
 * +----------------------------------------------------------------------+
 * | Copyright (c) 2016-Present ZhiYiChuangXiang Technology Co., Ltd.     |
 * +----------------------------------------------------------------------+
 * | This source file is subject to enterprise private license, that is   |
 * | bundled with this package in the file LICENSE, and is available      |
 * | through the world-wide-web at the following url:                     |
 * | https://github.com/slimkit/plus/blob/master/LICENSE                  |
 * +----------------------------------------------------------------------+
 * | Author: Slim Kit Group <master@zhiyicx.com>                          |
 * | Homepage: www.thinksns.com                                           |
 * +----------------------------------------------------------------------+
 */

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Admin;

use Illuminate\Http\Request;
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\MusicSinger;
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\MusicSinger as MusicSingerModel;
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Requests\SingerAdd as SingerAddRequest;
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Requests\SingerUpdate as SingerUpdateRequest;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\view;
use Zhiyi\Plus\Cdn\UrlManager as CdnUrlManager;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Models\FileWith as FileWithModel;

class SingerController extends Controller
{
    /**
     * 歌手列表.
     * @param  Request  $request
     * @param  MusicSingerModel  $musicSinger
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
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

    protected function search(MusicSinger $musicSinger, string $type, $name, $limit)
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
     * @param int $singer
     * @param MusicSingerModel $singerModel
     * @return \Illuminate\Http\RedirectResponse
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
     * @param CdnUrlManager $cdn
     * @param FileWithModel $fileWith
     * @param int|int $width
     * @param int|int $height
     * @param int|int $quality
     * @return string
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
     * @param SingerAddRequest $request
     * @param MusicSingerModel $singer
     * @return \Illuminate\Http\RedirectResponse
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
     * @param MusicSingerModel $singer [description]
     * @param FileWithModel $fileWith
     * @param CdnUrlManager $cdn
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
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
     * @param MusicSingerModel $singer
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
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
     * @param SingerAddRequest $request [description]
     * @return FileWithModel
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
     * @param SingerUpdateRequest $request [description]
     * @return FileWithModel
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
     * @return void
     */
    protected function saveFileWith($fileWith, MusicSingerModel $singer)
    {
        $fileWith->channel = 'music:singer:cover';
        $fileWith->raw = $singer->id;
        $fileWith->save();
    }
}
