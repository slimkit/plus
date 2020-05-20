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

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentNews\AdminControllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\News;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\NewsPinned;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Models\UserCount as UserCountModel;
use Zhiyi\Plus\Packages\Currency\Processes\User as UserProcess;

class NewsPinnedController extends Controller
{
    /**
     * 获取置顶审核列表.
     *
     * @author bs<414606094@qq.com>
     * @param  Request    $request
     * @param  NewsPinned $newsPinnedModel
     * @return mixed
     */
    public function index(Request $request, NewsPinned $newsPinnedModel)
    {
        $limit = $request->query('limit', 20);
        $max_id = $request->query('max_id', 0);
        $user = $request->query('user');
        $state = $request->query('state');
        $category = $request->query('cate_id');

        $pinneds = $newsPinnedModel->with('news', 'user')
            ->where('channel', 'news')
            ->when($max_id, function ($query) use ($max_id) {
                return $query->where('id', '<', $max_id);
            })
            ->when(isset($state), function ($query) use ($state) {
                switch ($state) {
                    case 0:
                        $query->where('state', 0)->where('expires_at', null);
                        break;
                    case 1:
                        $query->where('state', 1)->where('expires_at', '!=', null);
                        break;
                    case 2:
                        $query->where('state', 2)->where('expires_at', '!=', null);
                        break;
                }
            })
            ->when($user, function ($query) use ($user) {
                return $query->where('user_id', $user);
            })
            ->when($category, function ($query) use ($category) {
                return $query->where('cate_id', $category);
            })
            ->whereExists(function ($query) {
                return $query->from('news')->whereRaw('news.id = news_pinneds.target')->where('deleted_at', null);
            })
            ->orderBy('id', 'desc')
            ->limit($limit)
            ->get();

        return response()->json($pinneds, 200);
    }

    /**
     * 审核资讯置顶.
     *
     * @author bs<414606094@qq.com>
     * @param  Request    $request
     * @param  NewsPinned $pinned
     * @return mixed
     */
    public function audit(Request $request, UserProcess $userProcess, NewsPinned $pinned)
    {
        $action = $request->input('action', 'accept');

        if (! in_array($action, ['accept', 'reject'])) {
            abort(404);
        }

        if ($pinned->expires_at !== null) {
            return response()->json(['message' => ['该记录已被处理']], 403);
        } elseif ($action === 'accept') {
            return $this->accept($pinned);
        }

        return $this->reject($userProcess, $pinned);
    }

    public function accept(NewsPinned $pinned)
    {
        $pinned->state = 1;
        $pinned->expires_at = (new Carbon)->addDay($pinned->day);
        $pinned->save();

        // 审核通过后增加未读数
        $userCount = UserCountModel::firstOrNew([
            'user_id' => $pinned->user_id,
            'type' => 'user-system',
        ]);

        $userCount->total += 1;
        $userCount->save();

        $pinned->user->sendNotifyMessage('news:pinned:accept', sprintf('你申请的资讯《%s》已被置顶', $pinned->news->title), [
            'news' => $pinned->news,
            'pinned' => $pinned,
        ]);

        return response()->json([], 204);
    }

    public function reject(UserProcess $userProcess, NewsPinned $pinned)
    {
        $pinned->state = 2;
        $pinned->expires_at = new Carbon;
        $userCount = UserCountModel::firstOrNew([
            'user_id' => $pinned->user_id,
            'type' => 'user-system',
        ]);

        $userCount->total += 1;
        $pinned->getConnection()->transaction(function () use ($pinned, $userProcess, $userCount) {
            $pinned->save();
            $userCount->save();
            $newTitile = $pinned->news->title;
            $body = sprintf('资讯《%s》的置顶申请已被驳回，退还%s积分', $newTitile, $pinned->amount);
            $userProcess->receivables(
                $pinned->user_id,
                $pinned->amount,
                $pinned->news->user_id,
                '退还资讯置顶申请费用',
                $body
            );
            $pinned->user->sendNotifyMessage(
                'news:pinned:reject',
                $body,
                ['news' => $pinned->news, 'pinned' => $pinned]
            );
        });

        return response()->json(null, 204);
    }

    /**
     * 后台设置置顶.
     *
     * @author bs<414606094@qq.com>
     * @param  Request $request
     * @param  News    $news
     * @param  Carbon  $datetime
     */
    public function set(Request $request, News $news, Carbon $datetime)
    {
        $time = $request->input('time');
        $datetime = $datetime->createFromTimestamp($time);

        if (! $pinned = $news->pinned()->whereDate('expires_at', '>=', Carbon::now())->first()) {
            $pinned = new NewsPinned();
            $pinned->user_id = $news->user_id;
            $pinned->target = $news->id;
            $pinned->channel = 'news';
            $pinned->target_user = 0;
            $pinned->amount = 0;
        }
        $pinned->state = 1;
        $pinned->day = $datetime->diffInDays(Carbon::now());
        $pinned->expires_at = $datetime;
        $pinned->save();

        $pinned->user->sendNotifyMessage('news:pinned:accept', sprintf('你的资讯《%s》已被管理员设置为置顶', $news->title), [
            'news' => $news,
            'pinned' => $pinned,
        ]);

        return response()->json(['message' => ['操作成功']], 201);
    }

    /**
     * 取消后台资讯.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\News $news
     * @param \Illuminate\Support\Carbon $datetime
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function cancel(News $news, Carbon $datetime)
    {
        if (! $pinned = $news->pinned()->whereDate('expires_at', '>=', $datetime)->first()) {
            return response()->json(['message' => ['该资讯没有被置顶']], 402);
        }

        $pinned->expires_at = $datetime;
        $pinned->save();

        return response()->json(['message' => ['设置成功']], 201);
    }
}
