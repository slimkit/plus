<?php

namespace Zhiyi\PlusGroup\API\Controllers;

use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Zhiyi\PlusGroup\Models\Post;
use Zhiyi\PlusGroup\Models\Group;
use Zhiyi\PlusGroup\Models\Category;
use Zhiyi\PlusGroup\Models\GroupMember;
use Illuminate\Database\Eloquent\Model;
use Zhiyi\Plus\Models\FileWith as FileWithModel;
use Zhiyi\PlusGroup\API\Requests\CreateFeedRequest;
use Zhiyi\PlusGroup\Repository\Post as PostRepository;
use Zhiyi\PlusGroup\API\Requests\CreateGroupPostRequest;
use Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed as FeedModel;

class PostController
{
	/**
	 * Get post list.
     *
	 * @return json.
	 */
    public function index(Request $request, Group $group, PostRepository $repository)
    {
        $limit = $request->get('limit', 15);
        $offset = $request->get('offset', 0);
        $type = $request->get('type');

        $posts = $group->posts()->when($type && $type == 'latest_reply', function ($query) use ($type) {
            return $query->leftJoin('comments', function ($join){
                $join->on('group_posts.id', '=', 'comments.commentable_id')
                    ->where('commentable_type', '=', 'group-posts')
                    ->orderBy('comments.created_at', 'desc');
            })
            ->orderBy('comments.created_at', 'desc');
        }, function ($query) {
            return $query->orderBy('id', 'desc');
        })
        ->select([
            'group_posts.id',
            'group_posts.group_id',
            'group_posts.title',
            'group_posts.user_id',
            'group_posts.summary',
            'group_posts.likes_count',
            'group_posts.views_count',
            'group_posts.comments_count',
            'group_posts.created_at'
        ])
        ->with(['user', 'images'])
        ->offset($offset)
        ->limit($limit)
        ->get();

        $user = $request->user('api') ?? 0;

        $items = $posts->map(function ($post) use ($user, $repository) {
            $repository->formatCommonList($user, $post);

            return $post;
        });

        return response()->json([
            'pinneds' => ($type == 'latest_reply' || $offset > 0) ? collect([]) : app()->call([$this, 'pinneds'], ['group' => $group]),
            'posts' => $items,
        ], 200);
    }

    /**
     * 获取一个圈子的置顶帖子.
     *
     * @param Request $request
     * @param Group $group
     * @param Carbon $datetime
     * @return mixed
     * @author BS <414606094@qq.com>
     */
    public function pinneds(Request $request, Group $group, Carbon $datetime, PostRepository $repository)
    {
        $user = $request->user();

        $pinneds = $group->posts()->whereHas('pinned', function ($query) use ($datetime) {
            return $query->where('expires_at', '>', $datetime);
        })
        ->with(['user', 'images'])
        ->get();

        return $pinneds->map(function ($post) use ($user, $repository) {
            $repository->formatCommonList($user, $post);

            return $post;
        });
    }


    /**
     * Get a post.
     *
     * @param Post $post
     */
    public function show(Request $request, Group $group, Post $post, PostRepository $repository)
    {
        $user = $request->user('api') ?? 0;

        $post->increment('views_count');
        
        $repository->formatCommonDetail($user, $post);

        return response()->json($post, 200);
    }

    /**
     * 发布帖子.
     *
     * @param Request $request
     * @param $group
     */
    public function store(CreateGroupPostRequest $request, Group $group, PostRepository $repository)
    {
        $user = $request->user();

        if ($group->audit !== 1) {
            return response()->json(['message' => '圈子审核未通过或被拒绝'], 403);
        }

        $member = GroupMember::where('user_id', $user->id)
            ->where('group_id', $group->id)
            ->first();

        if (is_null($member)) {
            return response()->json(['message' => '未加入该圈子,不能进行发帖'], 403);
        }

        if ($member->audit != 1 || $member->disabled == 1) {
            return response()->json(['message' => '审核未通过或已被拉黑,不能进行发帖']);
        }

        if (! in_array($member->role, explode(',', $group->permissions))) {
            return response()->json(['message' => '没有发帖权限'], 422);
        }

        $fileWiths = $this->makeFileWith($request);

        DB::beginTransaction();

        try {

            $post = Post::create($this->fillRequestData($request, $group));

            $group->increment('posts_count');

            // save file.
            $this->saveFileWithByModel($post, $fileWiths);

            // sync to feed.
            $this->syncPostToFeed($request, $group, $fileWiths);

            DB::commit();
            return response()->json(['message' => '操作成功', 'post' => $repository->formatCommonDetail($user, $post)], 201);
        } catch (\Exception $e) {

            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Update a group post.
     *
     * @param CreateGroupPostRequest $request
     * @param Group $group
     * @param Post $post
     */
    public function update(CreateGroupPostRequest $request, Group $group, Post $post, PostRepository $repository)
    {
        $user = $request->user();

        if ($post->user_id !== $user->id) {
            return response()->json(['message' => '无权限操作'], 403);
        }

        $fileWiths = $this->makeFileWith($request);

        DB::beginTransaction();

        try {

            if ($fileWiths->count()) {
                FileWithModel::where('raw', $post->id)->where('channel', 'group:post:image')->delete();
            }

            $post->update($this->fillRequestData($request, $group));

            // save file.
            $this->saveFileWithByModel($post, $fileWiths);

            // sync to feed.
            $this->syncPostToFeed($request, $group, $fileWiths);

            DB::commit();
            return response()->json(['message' => '操作成功', 'post' => $repository->formatCommonDetail($user, $post)], 201);
        } catch (\Exception $e) {

            DB::rollback();
            return response()->json(['message', $e->getMessage()], 500);
        }

    }

    /**
     * Fill request data.
     *
     * @param CreateGroupPostRequest $request
     * @param Group $group
     * @return array
     */
    protected function fillRequestData(CreateGroupPostRequest $request, Group $group)
    {
        $data = $request->only('title', 'body', 'summary');

        $data = array_merge($data, ['user_id' => $request->user()->id, 'group_id' => $group->id]);

        return $data;
    }

    /**
     * Sync to feed.
     *
     * @param Request $request
     * @param $fileWiths
     * @throws \Exception
     */
    protected function syncPostToFeed(Request $request, Group $group, $fileWiths)
    {
        $sync = (int) $request->input('sync_feed');

        if ($group->allow_feed && $sync == 1) {
            $user = $request->user();
            $feed = $this->fillFeedBaseData($request, new FeedModel());
            $feed->saveOrFail();
            $feed->getConnection()->transaction(function () use ($feed, $fileWiths, $user) {
                $this->saveFileWithByModel($feed, $fileWiths);
                $user->extra()->firstOrCreate([])->increment('feeds_count', 1);
            });
        }
    }

    /**
     * Fill initial feed data.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed $feed
     * @return \Zhiyi\Component\ZhiyiPlus\PlusComponentFeed\Models\Feed
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function fillFeedBaseData(Request $request, FeedModel $feed): FeedModel
    {
        $feed->feed_from = $request->input('feed_from');
        $feed->feed_mark = $request->user()->id.time();
        $feed->feed_content = $request->input('summary');
        $feed->feed_client_id = $request->ip();
        $feed->audit_status = 1;
        $feed->user_id = $request->user()->id;

        return $feed;
    }


    /**
     * 创建文件使用模型.
     *
     * @param StoreFeedPostRequest $request
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function makeFileWith(Request $request)
    {
        return FileWithModel::whereIn(
            'id',
            array_filter($request->input('images', []))
        )->where('channel', null)
            ->where('raw', null)
            ->where('user_id', $request->user()->id)
            ->get();
    }

    /**
     * By model save file with.
     *
     * @param Model $model
     * @param Request $request
     * @param $fileWiths
     */
    protected function saveFileWithByModel(Model $model, $fileWiths)
    {
        if ($model instanceof Post) {
            foreach ($fileWiths as $fileWith) {
                $fileWith->channel = 'group:post:image';
                $fileWith->raw = $model->id;
                $fileWith->save();
            }
        } else {
            foreach ($fileWiths as $fileWith) {
                $fileWithModal = new FileWithModel();
                $fileWithModal->user_id = request()->user()->id;
                $fileWithModal->file_id = $fileWith->file_id;
                $fileWithModal->channel = 'feed:image';
                $fileWithModal->size = $fileWith->size;
                $fileWithModal->raw = $model->id;
                $fileWithModal->save();
            }
        }
    }

    /**
     * Delete post.
     *
     * @param Post $post
     */
    public function delete(Group $group, Post $post)
    {
        $user = request()->user();

        $member = GroupMember::select('role')
            ->where('user_id', $user->id)
            ->where('group_id', $group->id)
            ->first();
        
        if ($group->id != $post->group_id) {
            return response()->json(['message' => '操作资源不匹配'], 403);
        }

        if (is_null($member)) {
            return response()->json(['message' => '无操作权限'], 403);
        }

        if ($post->user_id != $user->id && !in_array($member->role, ['administrator', 'founder'])) {
            return response()->json(['message' => '无操作权限'], 403);
        }

        $post->delete();
        $group->decrement('posts_count');

        return response()->json(null, 204);
    }

    /**
     * 我的帖子列表.
     *
     * @param Request $request
     * @return mixed
     */
    public function userPosts(Request $request, Carbon $datetime)
    {
        $limit = $request->get('limit', 15);
        $offset = $request->get('offset', 0);
        $type = $request->get('type', 1); // 1-发布的 2- 已置顶 3-置顶待审

        $user = $request->user();

        $posts = Post::with(['user'])
            ->when($type > 1, function ($query) use ($type, $datetime) {
                switch ($type) {
                    case 2:
                        return $query->whereHas('pinned', function ($query) use ($datetime) {
                            return $query->where('expires_at', '>', $datetime);
                        });
                        break;
                    case 3:
                        return $query->whereHas('pinned', function ($query) use ($datetime) {
                            return $query->whereNull('expires_at');
                        });
                        break;
                }
            })
            ->where('user_id', $user->id)
            ->orderBy('id', 'desc')
            ->limit($limit)
            ->offset($offset)
            ->get();

        $items = $posts->map(function ($post) use ($user) {
            $post->collected = $post->collected($user);
            $post->liked = $post->liked($user);
            return $post;
        }); 

        return response()->json($items, 200);
    }

    /**
     * 全部帖子.
     *
     * @param Request $request
     * @return mixed
     */
    public function posts(Request $request, PostRepository $repository)
    {
        $userId = $request->user('api')->id ?? 0;

        $keyword = $request->get('keyword');
        $limit = (int) $request->query('limit', 15);
        $offset = (int) $request->get('offset', 0);
        $groupId = (int) $request->get('group_id', 0);

        $builder = Post::with(['user', 'group']);

        $posts = $builder->when($keyword, function ($query) use ($keyword) {
            return $query->where('title', 'like', sprintf('%%%s%%', $keyword));
        })
        ->when($userId, function ($query) use ($userId) {
            // 登陆状态 可以检索public和已加入圈子的帖子
           return $query->whereHas('group.members', function ($query) use ($userId) {
               return $query->where('audit', 1)->where('user_id', $userId)
                   ->where('disabled', 0)->whereIn('mode', ['public', 'paid', 'private'])->orWhere('mode', 'public');
           });
        }, function ($query) {
            // 未登陆 只能搜索mode为public下面帖子
           return $query->whereHas('group', function ($query) {
                return $query->where('mode', 'public');
           });
        })
        ->when($groupId, function ($query) use ($groupId) {
            return $query->where('group_id', $groupId);
        })
        ->whereHas('group', function ($query) {
            return $query->where('audit', 1);
        })
        ->orderBy('id', 'desc')
        ->offset($offset)
        ->limit($limit)
        ->get();

        $user = $request->user('api')->id ?? null;

        $items = $posts->map(function ($post) use ($user, $repository) {
            $repository->formatCommonList($user, $post); 

            return $post;
        });

        return response()->json($items, 200);
    }
}
