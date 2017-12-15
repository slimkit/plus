<?php

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentNews\API2\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Zhiyi\Plus\Http\Controllers\Controller;
use Zhiyi\Plus\Models\Comment as CommentModel;
use Zhiyi\Plus\Models\WalletCharge as WalletChargeModel;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseContract;
use Illuminate\Contracts\Foundation\Application as ApplicationContract;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\News as NewsModel;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\NewsPinned as NewsPinnedModel;

class PinnedController extends Controller
{
    /**
     * App.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * Create the controller instance.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function __construct(ApplicationContract $app)
    {
        $this->app = $app;
    }

    /**
     *  ask for top the comment.
     *  @author Wayne < qiaobinloverabbi@gmail.com >
     *  @param  Request      $request [description]
     *  @param  NewsModel    $news    [description]
     *  @param  CommentModel $comment [description]
     *  @return [type]
     */
    public function newsPinned(Request $request, NewsModel $news, Carbon $dateTime)
    {
        $user = $request->user();

        if ($user->id !== $news->user_id) {
            return response()->json(['message' => ['没有权限申请']], 403);
        }

        if ($news
            ->pinned()
            ->where('state', 1)
            ->where('expires_at', '>', $dateTime)
            ->count()
        ) {
            return response()->json(['message' => ['已经申请过']], 422);
        }

        if ($news
            ->pinned()
            ->where('state', 0)
            ->count()
        ) {
            return response()->json(['message' => ['已经申请过,请等待审核']], 422);
        }

        $pinned = new NewsPinnedModel();
        $pinned->user_id = $user->id;
        $pinned->target = $news->id;
        $pinned->channel = 'news';
        $pinned->target_user = 0;
        $pinned->state = 0;

        return $this->app->call([$this, 'PinnedValidate'], [
            'pinned' => $pinned,
            'call' => function (WalletChargeModel $charge, NewsPinnedModel $pinned) use ($user, $news) {
                $charge->user_id = $user->id;
                $charge->channel = 'user';
                $charge->account = $user->id;
                $charge->action = 0;
                $charge->amount = $pinned->amount;
                $charge->subject = '申请资讯置顶';
                $charge->body = sprintf('申请资讯《%s》置顶', $news->title);
                $charge->status = 1;

                return $this->app->call([$this, 'save'], [
                    'charge' => $charge,
                    'pinned' => $pinned,
                    'call' => null,
                ]);
            },
        ]);
    }

    /**
     *  ask for top the comment.
     *  @author Wayne < qiaobinloverabbi@gmail.com >
     *  @param  Request      $request [description]
     *  @param  NewsModel    $news    [description]
     *  @param  CommentModel $comment [description]
     *  @return [type]
     */
    public function commentPinned(Request $request, NewsModel $news, CommentModel $comment, Carbon $dateTime)
    {
        $user = $request->user();

        if ($user->id !== $comment->user_id) {
            return response()->json(['message' => ['没有权限申请']], 403);
        }

        if ($news
            ->pinnedComments()
            ->newPivotStatementForId($comment->id)
            ->where('user_id', $user->id)
            ->where('channel', 'news:comment')
            ->where('state', 1)
            ->where('expires_at', '>', $dateTime)
            ->count()
        ) {
            return response()->json(['message' => ['已经申请过']], 422);
        }

        if ($news
            ->pinnedComments()
            ->newPivotStatementForId($comment->id)
            ->where('user_id', $user->id)
            ->where('channel', 'news:comment')
            ->where('state', 0)
            ->count()
        ) {
            return response()->json(['message' => ['已经申请过,请等待审核']], 422);
        }

        $pinned = new NewsPinnedModel();
        $pinned->user_id = $user->id;
        $pinned->raw = $comment->id;
        $pinned->target = $news->id;
        $pinned->channel = 'news:comment';
        $pinned->target_user = $news->user_id ?? 0;
        $pinned->state = 0;

        return $this->app->call([$this, 'PinnedValidate'], [
            'pinned' => $pinned,
            'call' => function (WalletChargeModel $charge, NewsPinnedModel $pinned) use ($user, $comment, $news) {
                $charge->user_id = $user->id;
                $charge->channel = 'user';
                $charge->account = $user->id;
                $charge->action = 0;
                $charge->amount = $pinned->amount;
                $charge->subject = '申请资讯评论置顶';
                $charge->body = sprintf('申请评论《%s》置顶', $comment->body);
                $charge->status = 1;

                return $this->app->call([$this, 'save'], [
                    'charge' => $charge,
                    'pinned' => $pinned,
                    'call' => $news->user ? function () use ($user, $comment, $news, $pinned) {
                        $message = sprintf('%s 在你发布的资讯中申请评论置顶', $user->name);
                        $news->user->sendNotifyMessage('news:pinned-comment', $message, [
                            'news' => $news,
                            'user' => $user,
                            'comment' => $comment,
                            'pinned' => $pinned,
                        ]);
                    } : null,
                ]);
            },
        ]);
    }

    /**
     * 保存所有数据库记录.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \Zhiyi\Plus\Models\WalletCharge $charge
     * @param \Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\FeedPinned $pinned
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function save(Request $request,
                         ResponseContract $response,
                         WalletChargeModel $charge,
                         NewsPinnedModel $pinned,
                         callable $call = null)
    {
        $user = $request->user();
        $user->getConnection()->transaction(function () use ($user, $charge, $pinned) {
            $user->wallet()->decrement('balance', $charge->amount);
            $user->walletCharges()->save($charge);
            $pinned->save();
        });

        if ($call !== null) {
            call_user_func($call);
        }

        return $response->json(['message' => ['申请成功']])->setStatusCode(201);
    }

    /**
     *  check request.
     *  @author Wayne < qiaobinloverabbi@gmail.com >
     *  @param  Request         $request [description]
     *  @param  NewsPinnedModel $pinned  [description]
     *  @param  callable        $call    [description]
     *  @return [type]
     */
    public function PinnedValidate(Request $request, NewsPinnedModel $pinned, callable $call)
    {
        $user = $request->user();
        $rules = [
            'amount' => [
                'required',
                'integer',
                'min:1',
                'max:'.$user->wallet->balance,
            ],
            'day' => [
                'required',
                'integer',
                'min:1',
            ],
        ];
        $messages = [
            'amount.required' => '请输入金额',
            'amount.integer' => '参数有误',
            'amount.min' => '输入金额有误',
            'amount.max' => '余额不足',
            'day.required' => '请输入申请天数',
            'day.integer' => '天数只能为整数',
            'day.min' => '天数最少为1天',
        ];
        $this->validate($request, $rules, $messages);

        $pinned->amount = intval($request->input('amount'));
        $pinned->day = intval($request->input('day'));

        return $this->app->call($call, [
            'pinned' => $pinned,
        ]);
    }
}
