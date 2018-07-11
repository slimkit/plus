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
use Zhiyi\Plus\Models\PaidNode as PaidNodeModel;
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\Music;
use function Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\view;
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\MusicSinger;
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Models\MusicSpecial;
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Requests\MusicAdd as MusicAddRequest;
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Requests\SpecialAdd as SpecialAddRequest;
use Zhiyi\Component\ZhiyiPlus\PlusComponentMusic\Requests\SpecialUpdate as SpecialUpdateRequest;

class HomeController extends Controller
{
    /**
     * 管理后台入口.
     *
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function show(Request $request, Music $music)
    {
        $name = $request->query('name', '');
        $album = $request->query('album', '');
        $limit = $request->query('limit', 20);

        $musicList = $music->when($name, function ($query) use ($name) {
            return $query->where('title', 'like', "%{$name}%");
        })
            ->when($album, function ($query) use ($album) {
                $query->whereHas('musicSpecials', function ($query) use ($album) {
                    return $query->where('title', 'like', "%{$album}%");
                });
            })
            ->with([
                'musicSpecials',
                'singer',
                'paidNode',
            ])
            ->orderBy('sort', 'desc')
            ->paginate($limit);

        return view('music', [
            'base_url' => route('music:list'),
            'name' => $name,
            'album' => $album,
            'music' => $musicList->items(),
            'page' => $musicList->links(),
        ]);
    }

    /**
     * 删除音乐.
     * @param  Music  $music [description]
     * @return [type]        [description]
     */
    public function handleDelete(Music $music)
    {
        $music->delete();

        return back()->with(['success-message' => '删除成功']);
    }

    /**
     * 查看专辑.
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function showSpecial(Request $request, MusicSpecial $special)
    {
        $name = $request->query('name', '');
        $limit = $request->query('limit', 20);

        $specials = $special->when($name, function ($query) use ($name) {
            return $query->where('title', 'like', sprintf('%%%s%%', $name));
        })
            ->with('paidNode')
            ->withCount('musics')
            ->orderBy('sort', 'desc')
            ->paginate($limit);

        return view('special', [
            'base_url' => route('music:special'),
            'name' => $name,
            'special' => $specials->items(),
            'page' => $specials->links(),
        ]);
    }

    /**
     * 显示专辑详情.
     * @param  Request      $request [description]
     * @param  MusicSpecial $special [description]
     * @return [type]                [description]
     */
    public function specialDetail(MusicSpecial $special, CdnUrlManager $cdn)
    {
        $special->load(['storage', 'paidNode']);
        $storage = FileWithModel::find($special->storage);
        $special->url = $this->showStorage($cdn, $storage);
        $storage->load('file');
        $special->file = $storage->file;

        return view('specialEdit', [
            'special' => $special,
        ]);
    }

    /**
     * Get file.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \Zhiyi\Plus\Cdn\UrlManager $manager
     * @param \Zhiyi\Plus\Models\FileWith $fileWith
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function showStorage(CdnUrlManager $cdn, FileWithModel $fileWith, int $width = 200, int $height = 200, int $quality = 80)
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
     * 禁用专辑.
     * @param  MusicSpecial $special [description]
     * @return [type]                [description]
     */
    public function handleDisableSpecial(MusicSpecial $special)
    {
        $special->delete();

        return back()->with(['success-message' => '禁用专辑成功']);
    }

    /**
     * 增加专辑页面.
     * @return [type] [description]
     */
    public function handleAddSpecial()
    {
        return view('specialAdd');
    }

    /**
     * 保存专辑.
     * @param  SpecialAddRequest $request [description]
     * @param  MusicSpecial      $special [description]
     * @return [type]                     [description]
     */
    public function handleStoreSpecial(SpecialAddRequest $request, MusicSpecial $special)
    {
        $data = $request->only(['title', 'pay-node', 'amount', 'sort', 'storage', 'intro']);
        $special->title = $data['title'];
        $special->sort = $data['sort'];
        $special->intro = $data['intro'];
        $special->storage = $data['storage'];

        $fileWith = $this->makeSpecialFileWith($request);
        try {
            $special->saveOrFail();
            $special->getConnection()->transaction(function () use ($special, $request, $fileWith) {
                $paidNode = $this->makeSpecialNode($request, $special);
                $this->saveSpecialFileWith($fileWith, $special);
                $paidNode && $this->saveSpecialPaidNode($paidNode, $special);
            });
        } catch (\Exception $e) {
            $special->delete();
            throw $e;
        }

        return back()->with(['success-message' => '创建专辑成功']);
    }

    /**
     * 更改专辑.
     * @param  Request      $request [description]
     * @param  MusicSpecial $special [description]
     * @return [type]                [description]
     */
    public function handleUpdateSpecial(SpecialUpdateRequest $request, MusicSpecial $special, FileWithModel $fileWithModel)
    {
        $data = $request->only(['title', 'storage', 'intro', 'sort', 'paid_node', 'amount']);

        $paidNode = null;
        $fileWith = null;
        if ($data['storage'] && $data['storage'] !== $special->storage) {
            if ($fileWithModel->where('id', $data['storage'])
                ->where('channel', null)
                ->where('raw', null)
                ->count()
            ) {
                $fileWithModel
                    ->where('channel', 'music:special:storage')
                    ->where('raw', $special->id)
                    ->forceDelete();
                $special->storage = $data['storage'];
                $fileWith = $this->updateSpecialFileWith($request);
            }
        }

        if (! $data['paid_node']) {
            $data['paid_node'] = ['free'];
        }

        $special->paidNode()->delete();

        if (in_array('need-pay', $data['paid_node'])) {
            $paidNode = $this->updateSpecialNode($request, $special);
        }

        $special->title = $data['title'];
        $special->intro = $data['intro'];
        $special->sort = $data['sort'];

        try {
            $special->saveOrFail();
            $special->getConnection()->transaction(function () use ($special, $request, $paidNode, $fileWith) {
                $fileWith && $this->saveSpecialFileWith($fileWith, $special);
                $paidNode && $this->saveSpecialPaidNode($paidNode, $special);
            });
        } catch (\Exception $e) {
            // $special->rollBack();
            throw $e;
        }

        return back()->with(['success-message' => '更新专辑成功']);
    }

    /**
     * 歌曲详情.
     * @param  Music        $music   [description]
     * @param  MusicSpecial $special [description]
     * @param  MusicSinger  $singer  [description]
     * @return [type]                [description]
     */
    public function handleAddMuisc(MusicSpecial $special, MusicSinger $singer)
    {
        $specials = $special->get();
        $singers = $singer->get();

        return view('musicAdd', [
            'specials' => $specials,
            'singers' => $singers,
        ]);
    }

    public function handleStoreMuisc(MusicAddRequest $request, Music $music)
    {
        $music->title = $request->input('title');
        $music->last_time = (int) $request->input('last_time', 0);
        $music->storage = (int) $request->input('storage');
        $music->sort = (int) $request->input('sort', 0);
        $music->singer = (int) $request->input('singer');
        $music->lyric = $request->input('lyric');

        // 创建收费节点
        $paidNodes = $this->makePaidNode($request);
        $fileWith = $this->makeFileWith($request);

        try {
            $music->saveOrFail();
            if ($request->input('special')) {
                $music->musicSpecials()->attach($request->input('special'));
            }
            $music->getConnection()->transaction(function () use ($request, $music, $paidNodes, $fileWith) {
                $this->saveMusicPaidNode($paidNodes, $music);
                $this->saveMusicFileWith($fileWith, $music);
            });
        } catch (\Exception $e) {
            $music->delete();
            throw $e;
        }

        return back()->with(['success-message' => '添加歌曲成功']);
    }

    /**
     * 创建付费节点模型.
     *
     * @param StoreFeedPostRequest $request
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function makePaidNode(MusicAddRequest $request)
    {
        $paid_node = (array) $request->input('paid_node');
        if (in_array('download', $paid_node) || in_array('listen', $paid_node)) {
            return collect($request->input('paid_node'))->map(function ($paid) use ($request) {
                $paidNode = new PaidNodeModel();
                $paidNode->channel = 'file';
                $paidNode->raw = $request->input('storage');
                $paidNode->amount = $request->input($paid) * 100;
                $paidNode->extra = $paid;

                return $paidNode;
            });
        }

        return null;
    }

    /**
     * 创建专辑付费节点.
     * @param  SpecialAddRequest $request [description]
     * @return [type]                     [description]
     */
    protected function makeSpecialNode(SpecialAddRequest $request, MusicSpecial $special)
    {
        $paid_node = (array) $request->input('paid_node');
        if (in_array('need-pay', $paid_node)) {
            $paidNode = new PaidNodeModel();
            $paidNode->channel = 'music';
            $paidNode->raw = $special->id;
            $paidNode->amount = $request->input('amount') * 100;
            $paidNode->extra = 'read';

            return $paidNode;
        }

        return null;
    }

    /**
     * 创建专辑付费节点.
     * @param  SpecialAddRequest $request [description]
     * @return [type]                     [description]
     */
    protected function updateSpecialNode(SpecialUpdateRequest $request, MusicSpecial $special)
    {
        $paid_node = (array) $request->input('paid_node');
        if (in_array('need-pay', $paid_node)) {
            $paidNode = new PaidNodeModel();
            $paidNode->channel = 'music';
            $paidNode->raw = $special->id;
            $paidNode->amount = $request->input('amount') * 100;
            $paidNode->extra = 'read';

            return $paidNode;
        }

        return null;
    }

    /**
     * 创建文件使用模型.
     *
     * @param StoreFeedPostRequest $request
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function makeFileWith(MusicAddRequest $request)
    {
        return FileWithModel::where(
            'id',
            $request->input('storage')
        )
        ->where('channel', null)
        ->where('raw', null)
        ->where('user_id', $request->user()->id)
        ->first();
    }

    /**
     * 添加专辑设置封面.
     * @param  SpecialAddRequest $request [description]
     * @return [type]                     [description]
     */
    protected function makeSpecialFileWith(SpecialAddRequest $request)
    {
        return FileWithModel::where(
            'id',
            $request->input('storage')
        )->where('channel', null)
        ->where('raw', null)
        ->where('user_id', $request->user()->id)
        ->first();
    }

    /**
     * 保存需要更新的专辑设置封面.
     * @param  SpecialAddRequest $request [description]
     * @return [type]                     [description]
     */
    protected function updateSpecialFileWith(SpecialUpdateRequest $request)
    {
        return FileWithModel::where(
            'id',
            $request->input('storage')
        )->where('channel', null)
        ->where('raw', null)
        ->where('user_id', $request->user()->id)
        ->first();
    }

    /**
     * 保存歌曲使用.
     *
     * @param array $fileWiths
     * @param \Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed $feed
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function saveMusicFileWith($fileWith, Music $music)
    {
        $fileWith->channel = 'file';
        $fileWith->raw = $music->id;
        $fileWith->save();
    }

    /**
     * 保存专辑封面.
     * @param  [type]       $fileWith [description]
     * @param  MusicSpecial $special  [description]
     * @return [type]                 [description]
     */
    protected function saveSpecialFileWith($fileWith, MusicSpecial $special)
    {
        $fileWith->channel = 'music:special:storage';
        $fileWith->raw = $special->id;
        $fileWith->save();
    }

    /**
     * 保存分享文件付费节点.
     *
     * @param array $nodes
     * @param \Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed $feed
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function saveMusicPaidNode($nodes, Music $music)
    {
        if ($nodes) {
            foreach ($nodes as $node) {
                $node->subject = ($node->extra === 'download' ? '歌曲下载付费' : '听歌曲收费');
                $node->body = ($node->extra === 'download' ? '购买歌曲《'.$music->title.'》' : '下载歌曲《'.$music->title.'》');
                $node->user_id = 0;
                $node->save();
            }
        }
    }

    /**
     * 保存专辑收费节点.
     * @param  [type]       $node    [description]
     * @param  MusicSpecial $special [description]
     * @return [type]                [description]
     */
    protected function saveSpecialPaidNode($node, MusicSpecial $special)
    {
        $node->subject = '播放专辑';
        $node->body = '播放专辑《'.$special->title.'》';
        $node->user_id = 0;
        $node->save();
    }
}
