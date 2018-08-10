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

namespace Zhiyi\Component\ZhiyiPlus\PlusComponentNews\API2\Controllers;

use Illuminate\Http\Request;
use Zhiyi\Plus\Utils\Markdown;
use Zhiyi\Plus\Models\Tag as TagModel;
use Zhiyi\Plus\Concerns\FindMarkdownFileTrait;
use Zhiyi\Plus\Models\FileWith as FileWithModel;
use Zhiyi\Plus\Models\WalletCharge as WalletChargeModel;
use Zhiyi\Plus\Packages\Currency\Processes\User as UserProcess;
use Illuminate\Contracts\Foundation\Application as ApplicationContract;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\News as NewsModel;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseFactoryContract;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\NewsCate as NewsCateModel;
use Zhiyi\Component\ZhiyiPlus\PlusComponentNews\API2\Requests\StoreContribute as StoreContributeRequest;

class ContributeController extends Controller
{
    use FindMarkdownFileTrait;

    /**
     * 应用容器对象.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * 创建这个控制器实例.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function __construct(ApplicationContract $app)
    {
        $this->app = $app;
    }

    /**
     * 获取投稿列表.
     *
     * @param Request                 $request
     * @param ResponseFactoryContract $response
     * @param NewsModel               $model
     * @return mixed
     * @throws \Throwable
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function index(Request $request, ResponseFactoryContract $response, NewsModel $model)
    {
        $user = $request->user();
        $current_user = $request->query('user', $user->id);
        $after = $request->query('after');
        $limit = $request->query('limit', 20);
        $status = $request->query('type');

        // 只能查看他人正常发布的资讯
        if ($current_user !== $user->id) {
            $status = 0;
        }

        $news = $model->where('user_id', $current_user)
            ->when(isset($status), function ($query) use ($status) {
                return $query->where('audit_status', $status);
            })
            ->when($after, function ($query) use ($after) {
                return $query->where('id', '<', $after);
            })
            ->with([
                'tags',
                'user' => function ($query) {
                    return $query->withTrashed();
                },
            ])
            ->orderBy('id', 'desc')
            ->limit($limit)
            ->get();

        return $response->json($model->getConnection()->transaction(function () use ($user, $news) {
            return $news->each(function ($data) use ($user) {
                $data->has_collect = $data->collected($user);
                $data->has_like = $data->liked($user);
                unset($data->pinned);
            });
        }))->setStatusCode(200);
    }

    /**
     * 提交资讯投稿申请.
     *
     * @param \Zhiyi\Component\ZhiyiPlus\PlusComponentNews\API2\Requests\StoreContribute $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory                              $response
     * @param \Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\News                   $news
     * @param WalletChargeModel                                                          $charge
     * @param \Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\NewsCate               $category
     * @param TagModel                                                                   $tagModel
     * @return mixed
     * @throws \Throwable
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function store(
        StoreContributeRequest $request,
        ResponseFactoryContract $response,
        NewsModel $news,
        WalletChargeModel $charge,
        NewsCateModel $category,
        TagModel $tagModel
    ) {
        $user = $request->user();
        $config = config('news.contribute');
        $payAmount = config('news.pay_contribute');

        if ($config['pay'] && $user->wallet->balance < $payAmount) {
            return $response->json(['message' => '账户余额不足'], 422);
        }

        if ($config['verified'] && $user->verified === null) {
            return $response->json(['message' => '未认证用户不可投稿'], 422);
        }

        $map = $request->only(['title', 'content', 'subject', 'text_content']);
        $map['content'] = $this->app->make(Markdown::class)->safetyMarkdown($map['content']);
        $map['from'] = $request->input('from') ?: '原创';
        $map['author'] = $request->input('author') ?: $user->name;
        $map['storage'] = $request->input('image');

        $images = $this->findMarkdownImageNotWithModels($map['content'] ?: '');
        $images[] = $this->app->call(function (FileWithModel $fileWith) use ($map) {
            return $fileWith->where('id', $map['storage'])
                ->where('channel', null)
                ->where('raw', null)
                ->first();
        });
        $images = $images->filter();

        $tags = $tagModel->whereIn('id', is_array($request->input('tags')) ? $request->input('tags') : explode(',', $request->input('tags')))->get();
        if (! $tags) {
            return $response->json(['message' => '填写的标签不存在或已删除'], 422);
        }

        foreach ($map as $key => $value) {
            $news->$key = $value;
        }
        $news->digg_count = 0;
        $news->comment_count = 0;
        $news->hits = 0;
        $news->is_recommend = 0;
        $news->audit_status = 1;
        $news->audit_count = 0;
        $news->user_id = $user->id;
        $news->contribute_amount = $config['pay'] ? $payAmount : 0;

        if (! $category->news()->save($news)) {
            return $response->json(['message' => '投稿失败'])->setStatusCode(500);
        }

        $charge->user_id = $user->id;
        $charge->channel = 'system';
        $charge->action = 0;
        $charge->amount = $payAmount;
        $charge->subject = '支付资讯投稿费用';
        $charge->body = sprintf('支付资讯《%s》投稿费用', $news->title);
        $charge->status = 1;

        try {
            $category->getConnection()->transaction(function () use ($news, $images, $user, $charge, $config, $tags) {
                $images->each(function (FileWithModel $fileWith) use ($news) {
                    $fileWith->channel = 'news:image';
                    $fileWith->raw = $news->id;

                    $fileWith->save();
                });

                if ($config['pay']) {
                    $user->wallet()->decrement('balance', $charge->amount);
                    $charge->save();
                }

                $news->tags()->attach($tags);
            });

            return $response->json(['message' => '投稿成功'], 201);
        } catch (Exception $exception) {
            $news->delete();
            throw new $exception;
        }
    }

    /**
     * 修改投稿稿件.
     *
     * @param \Illuminate\Http\Request                                     $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory                $response
     * @param \Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\NewsCate $category
     * @param \Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\News     $news
     * @param TagModel                                                     $tagModel
     * @return mixed
     * @throws \Throwable
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function update(
        Request $request,
        ResponseFactoryContract $response,
        NewsCateModel $category,
        NewsModel $news,
        TagModel $tagModel
    ) {
        $user = $request->user();

        if ($news->user_id !== $user->id) {
            return $response->json(['message' => '你没有权限操作'], 403);

        // 非驳回状态，不允许编辑
        } elseif ($news->audit_status !== 3) {
            return $response->json(['message' => '当前状态不可编辑'], 422);

        // 申请退款，无法进行编辑
        } elseif ($news->audit_status === 5) {
            return $response->json(['message' => '退款中，无法修改'], 422);

        // 极端情况，一般审核超过三次，后台会删除，不排除不删除。
        } elseif ($news->audit_count >= 3) {
            return $response->json(['message' => '您没有权限修改'], 403);
        }

        $this->validate($request, [
            'title' => 'nullable|string|max:40',
            'subject' => 'nullable|string|max:400',
            'content' => 'nullable|string',
            'from' => 'nullable|string',
            'author' => 'nullable|string',
            'image' => 'nullable|int',
            'text_content' => 'nullable|string',
        ]);

        $map = $request->only(['title', 'subject', 'content', 'from', 'author', 'text_content']);
        $image = $this->app->call(function (FileWithModel $fileWith) use ($request) {
            $image = $request->input('image');

            return ! $image ? null : $fileWith->where('id', $image)
                ->where('channel', null)
                ->where('raw', null)
                ->first();
        });

        $images = collect([]);
        if ($map['content']) {
            $map['content'] = $this->app->make(Markdown::class)->safetyMarkdown($map['content']);
            $images = $this->findMarkdownImageNotWithModels($map['content']);
        }

        if ($image) {
            $map['storage'] = $image->id;
            $images[] = $image;
        }

        $tags = $tagModel->whereIn('id', is_array($request->input('tags')) ? $request->input('tags') : explode(',', $request->input('tags')))->get();
        if (! $tags) {
            return $response->json(['message' => '填写的标签不存在或已删除'], 422);
        }

        foreach (array_filter($map) as $key => $value) {
            $news->$key = $value;
        }

        return $category->getConnection()->transaction(function () use ($news, $images, $response, $tags) {
            $news->audit_status = 1;
            $news->save();
            $news->tags()->detach();
            $news->tags()->attach($tags);

            $images->each(function (FileWithModel $fileWith) use ($news) {
                $fileWith->channel = 'news:image';
                $fileWith->raw = $news->id;

                $fileWith->save();
            });

            return $response->make('', 204);
        });
    }

    /**
     * 删除投稿.
     *
     * @param \Illuminate\Http\Request                                     $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory                $response
     * @param \Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\NewsCate $category
     * @param \Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\News     $news
     * @return mixed
     * @throws \Throwable
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function destroy(
        Request $request,
        ResponseFactoryContract $response,
        NewsCateModel $category,
        NewsModel $news
    ) {
        $user = $request->user();

        if ($news->user_id !== $user->id) {
            return $response->json(['message' => '你没有权限操作'], 403);
        // 审核中
        } elseif ($news->audit_status === 1) {
            return $response->json(['message' => '审核中禁止删除'], 422);
        // 退款中
        } elseif ($news->audit_status === 5) {
            return $response->json(['message' => '退款中禁止删除'], 422);
        }

        return $category->getConnection()->transaction(function () use ($news, $response, $user) {
            if ($news->audit_status == 0) { // 已发布的需提交后台申请删除
                $news->applylog()->firstOrCreate(['user_id' => $user->id, 'news_id' => $news->id], ['status' => 0]);

                return $response->make(['message' => '删除申请已提交，请等待审核'], 201);
            }

            $news->delete();

            return $response->make('', 204);
        });
    }

    /**
     * 撤销投稿，申请退款.
     *
     * @param \Illuminate\Http\Request                                     $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory                $response
     * @param \Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\NewsCate $category
     * @param \Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\News     $news
     * @return mixed
     * @throws \Throwable
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function revoked(
        Request $request,
        ResponseFactoryContract $response,
        NewsCateModel $category,
        NewsModel $news
    ) {
        $user = $request->user();

        if ($user->id !== $news->user_id) {
            return $response->json(['message' => '你没有权限操作'], 403);
        } elseif ($news->audit_status === 5) {
            return $response->json(['message' => '请勿重复申请'], 422);
        } elseif ($news->audit_count > 2) {
            return $response->json(['message' => '驳回超过两次，已无法申请退款'], 422);
        }

        $news->audit_status = 5;

        return $category->getConnection()->transaction(function () use ($news, $response) {
            $news->save();

            return $response->json(['message' => '申请成功'], 201);
        });
    }

    /**
     * 提交资讯投稿申请.
     *
     * @param \Zhiyi\Component\ZhiyiPlus\PlusComponentNews\API2\Requests\StoreContribute $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory                              $response
     * @param \Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\News                   $news
     * @param \Zhiyi\Component\ZhiyiPlus\PlusComponentNews\Models\NewsCate               $category
     * @param TagModel                                                                   $tagModel
     * @return mixed
     * @throws \Throwable
     * @author BS <414606094@qq.com>
     */
    public function newStore(
        StoreContributeRequest $request,
        ResponseFactoryContract $response,
        NewsModel $news,
        NewsCateModel $category,
        TagModel $tagModel
    ) {
        $user = $request->user();
        $config = config('news.contribute');
        $payAmount = intval(config('news.pay_contribute'));

        if ($config['pay'] && $user->currency && $user->currency->sum < $payAmount) {
            return $response->json(['message' => '账户余额不足'], 403);
        }

        if ($config['verified'] && $user->verified === null) {
            return $response->json(['message' => '未认证用户不可投稿'], 403);
        }

        $map = $request->only(['title', 'content', 'subject']);
        $map['from'] = $request->input('from') ?: '原创';
        $map['author'] = $request->input('author') ?: $user->name;
        $map['storage'] = $request->input('image');

        $images = $this->findMarkdownImageNotWithModels($map['content'] ?: '');
        // 提取内容中的图片，用于列表种的多种UI展示
        $map['images'] = $images->map(function ($item) {
            return [
                'id' => $item->id,
                'width' => $item->file->width,
                'height' => $item->file->height,
                'mime' => $item->file->mime,
            ];
        });

        $images[] = $this->app->call(function (FileWithModel $fileWith) use ($map) {
            return $fileWith->where('id', $map['storage'])
                ->where('channel', null)
                ->where('raw', null)
                ->first();
        });
        $images = $images->filter();
        $tags = $tagModel->whereIn('id', is_array($request->input('tags')) ? $request->input('tags') : explode(',', $request->input('tags')))->get();
        if (! $tags) {
            return $response->json(['message' => '填写的标签不存在或已删除'], 422);
        }

        foreach ($map as $key => $value) {
            $news->$key = $value;
        }
        $news->digg_count = 0;
        $news->comment_count = 0;
        $news->hits = 0;
        $news->is_recommend = 0;
        $news->audit_status = 1;
        $news->audit_count = 0;
        $news->user_id = $user->id;
        $news->contribute_amount = $payAmount;

        if (! $category->news()->save($news)) {
            return $response->json(['message' => '投稿失败'])->setStatusCode(500);
        }

        try {
            $category->getConnection()->transaction(function () use ($news, $images, $user, $payAmount, $config, $tags) {
                $images->each(function (FileWithModel $fileWith) use ($news) {
                    $fileWith->channel = 'news:image';
                    $fileWith->raw = $news->id;

                    $fileWith->save();
                });

                if ($config['pay']) {
                    $process = new UserProcess();
                    $process->prepayment($user->id, $payAmount, 0, '支付资讯投稿所需积分', sprintf('支付资讯《%s》投稿所需积分', $news->title));
                }

                $news->tags()->attach($tags);
            });

            return $response->json(['message' => '投稿成功'], 201);
        } catch (Exception $exception) {
            $news->delete();
            throw new $exception;
        }
    }
}
