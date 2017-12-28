<?php

namespace Zhiyi\PlusGroup\Repository;

use Carbon\Carbon;
use Zhiyi\Plus\Models\User as UserModel;
use Zhiyi\PlusGroup\Models\Post as PostModel;

class Post
{
    protected $model;

    protected $user;

    protected $datetime;

    public function __construct(PostModel $model, Carbon $datetime)
    {
        $this->model = $model;
        $this->datetime = $datetime;
    }

    public function setModel(PostModel $model)
    {
        $this->model = $model;

        return $this;
    }

    public function setUser(UserModel $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * 格式化列表数据.
     *
     * @return Zhiyi\PlusGroup\Models\Post
     * @author BS <414606094@qq.com>
     */
    public function formatList()
    {
        // 帖子列表不返回详情内容
        $this->model->addHidden('body');

        return $this->model;
    }

    /**
     * 格式化图片内容.
     *
     * @return Zhiyi\PlusGroup\Models\Post
     * @author BS <414606094@qq.com>
     */
    public function formatImages()
    {
        $images = $this->model->images;

        unset($this->model->images);

        $this->model->images = $images->map(function ($image) {
            return ['id' => $image->id, 'size' => $image->size];
        });

        return $this->model;
    }

    /**
     * 规整帖子与当前用户关系.
     *
     * @return Zhiyi\PlusGroup\Models\Post
     * @author BS <414606094@qq.com>
     */
    public function formatRelations()
    {
        $this->model->collected = false;
        $this->model->liked = false;

        if (! $this->user) {
            return $this->model;
        }
        $this->model->collected = $this->model->collected($this->user);
        $this->model->liked = $this->model->liked($this->user);

        return $this->model;
    }

    /**
     * 检测帖子置顶状态.
     *
     * @return Zhiyi\PlusGroup\Models\Post
     * @author BS <414606094@qq.com>
     */
    public function formatPinned()
    {
        $this->model->pinned = $this->model->pinned()->where('expires_at', '>', $this->datetime)->first() ? true : false;

        return $this->model;
    }

    /**
     * 帖子列表取五条评论，置顶优先.
     *
     * @return Zhiyi\PlusGroup\Models\Post
     * @author BS <414606094@qq.com>
     */
    public function previewComments()
    {
        $comments = collect([]);

        $pinnedComments = $this->model->comments()->whereExists(function ($query) {
            return $query->from('group_pinneds')->whereRaw('group_pinneds.target = comments.id')
                ->where('channel', 'comment')
                ->where('expires_at', '>', $this->datetime);
        })
        ->with(['user', 'reply'])
        ->get();

        if ($pinnedComments->count() < 5) {
            $comments = $this->model->comments()
                ->limit(5 - $pinnedComments->count())
                ->whereNotIn('id', $pinnedComments->pluck('id'))
                ->with(['user', 'reply'])
                ->orderBy('id', 'desc')
                ->get();
        }

        $this->model->comments = $pinnedComments->map(function ($comment) {
            $comment->pinned = true;

            return $comment;
        })->merge($comments);

        return $this->model;
    }

    /**
     * 帖子打赏统计.
     *
     * @return Zhiyi\PlusGroup\Models\Post
     * @author BS <414606094@qq.com>
     */
    public function previewRewards()
    {
        $this->model->load('rewards');

        $rewards = $this->model->rewards;

        $this->model->reward_amount = (int) $rewards->sum('amount');
        $this->model->reward_number = $rewards->count();

        $this->model->addHidden('rewards');

        return $this->model;
    }

    /**
     * 常规帖子列表格式化.
     *
     * @param UserModel $user
     * @param PostModel $model
     * @return Zhiyi\PlusGroup\Models\Post
     * @author BS <414606094@qq.com>
     */
    public function formatCommonList($user, PostModel $model)
    {
        if ($user instanceof UserModel) {
            $this->setUser($user);
        } elseif ( $user = UserModel::find($user)) {
            $this->setUser($user);
        }

        $this->setModel($model);

        $this->formatRelations();
        $this->formatList();
        $this->previewComments();
        $this->formatImages();

        return $this->model;
    }

    /**
     * 常规帖子详情格式化.
     *
     * @param UserModel $user
     * @param PostModel $model
     * @return Zhiyi\PlusGroup\Models\Post
     * @author BS <414606094@qq.com>
     */
    public function formatCommonDetail($user, PostModel $model)
    {
        if ($user instanceof UserModel) {
            $this->setUser($user);
        } elseif ( $user = UserModel::find($user)) {
            $this->setUser($user);
        }

        $this->setModel($model);
        $this->model->load(['group', 'user']);

        $this->model->group->joined = isset($this->user) ? $this->model->group->members()->where('user_id', $this->user->id)->where('audit', 1)->first() : null;

        $this->formatPinned();
        $this->previewComments();
        $this->formatRelations();
        $this->formatImages();
        $this->previewRewards();

        return $this->model;
    }
}