<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentNews\AdminControllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Models\WalletCharge as WalletChargeModel;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\News;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\NewsPinned;

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

        $pinneds = $newsPinnedModel->with('news', 'user')
            ->where('channel', 'news')
            ->when($max_id, function ($query) use ($max_id) {
                return $query->where('id', '<', $max_id);
            })
            ->when($state, function ($query) use ($state) {
                switch ($state) {
                    case 1:
                        $query->where('expires_at', null);
                        break;
                    case 2:
                        $query->where('expires_at', null);
                        break;
                }
            })
            ->when($user, function ($query) use ($user) {
                return $query->where('user_id', $user);
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
    public function audit(Request $request, NewsPinned $pinned, Carbon $datetime)
    {
        $action = $request->input('action', 'accept');

        if (! in_array($action, ['accept', 'reject'])) {
            abort(404);
        }

        if ($pinned->expires_at !== null) {
            return response()->json(['message' => ['该记录已被处理']], 403);
        }

        return $this->{$action}($pinned, $datetime);
    }

    public function accept(NewsPinned $pinned, Carbon $datetime)
    {
        $pinned->state = 1;
        $pinned->expires_at = $datetime->addDay($pinned->day);

        $pinned->save();
        $pinned->user->sendNotifyMessage('news:pinned:accept', sprintf('你申请的资讯《%s》已被置顶', $pinned->news->title), [
            'news' => $pinned->news,
            'pinned' => $pinned,
        ]);

        return response()->json([], 204);
    }

    public function reject(NewsPinned $pinned, Carbon $datetime)
    {
        $pinned->state = 2;
        $pinned->expires_at = $datetime;

        $charge = new WalletChargeModel();
        $charge->user_id = $pinned->user_id;
        $charge->channel = 'system';
        $charge->action = 1;
        $charge->amount = $pinned->amount;
        $charge->subject = '退还资讯置顶申请费用';
        $charge->body = sprintf('退还资讯《%s》的置顶申请费用', $pinned->news->title);
        $charge->status = 1;

        $pinned->getConnection()->transaction(function () use ($pinned, $charge) {
            $pinned->save();
            $charge->save();

            $pinned->user->sendNotifyMessage('news:pinned:reject', sprintf('资讯《%s》的置顶申请已被驳回', $pinned->news->title), [
                'news' => $pinned->news,
                'pinned' => $pinned,
            ]);
        });

        return response()->json([], 204);
    }

    public function set(Request $request, News $news, Carbon $datetime)
    {
        $time = $request->query('time');
        $datetime = $datetime->createFromTimestamp($time);

        $pinned = new NewsPinned();
        $pinned->user_id = $news->user_id;
        $pinned->target = $news->id;
        $pinned->channel = 'news';
        $pinned->target_user = 0;
        $pinned->amount = 0;
        $pinned->day = $datetime->diffInDays(Carbon::now());
        $pinned->state = 1;
        $pinned->expires_at = $datetime;

        $pinned->save();
        $pinned->user->sendNotifyMessage('news:pinned:accept', sprintf('你的资讯《%s》已被管理员设置为置顶', $news->title), [
            'news' => $news,
            'pinned' => $pinned,
        ]);

        return response()->json(['message' => ['操作成功']], 201);
    }
}
