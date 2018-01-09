<?php

namespace Zhiyi\PlusGroup\Repository;

use Illuminate\Support\Facades\Cache;
use Zhiyi\Plus\Models\User as UserModel;
use Zhiyi\PlusGroup\Models\Group as GroupModel;

class Group
{
    protected $model;

    protected $user;

    // 成员信息缓存
    protected $memberCacheKey = 'group:%s:user:%s';

    protected $error;

    protected $status;

    /**
     * 设置圈子模型.
     *
     * @param GroupModel $model
     * @author BS <414606094@qq.com>
     */
    public function setModel(GroupModel $model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * 设置用户模型.
     *
     * @param UserModel $user
     * @author BS <414606094@qq.com>
     */
    public function setUser(UserModel $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * 验证圈子是否审核通过.
     *
     * @return boolean
     * @author BS <414606094@qq.com>
     */
    public function isAudit(): bool
    {
        if ($this->model->audit !== 1) {
            $this->error = '圈子审核未通过或被拒绝';
            $this->status = 422;

            return false
        }

        return true;
    }

    /**
     * 验证是否为公开圈子.
     *
     * @return boolean
     * @author BS <414606094@qq.com>
     */
    public function isPublic(): bool
    {
        if ($this->model->mode !== 'public') {
            $this->error = '该圈子为私密圈子，请加入后再操作';
            $this->status = 422;

            return false;
        }

        return true;
    }

    /**
     * 验证是否加入圈子.
     *
     * @return boolean [description]
     * @author BS <414606094@qq.com>
     */
    public function hasJoin(): bool
    {
        if (! $this->getMember()) {
            $this->error = '请先加入该圈子';
            $this->status = 422;

            return false;
        }

        return true;
    }

    /**
     * 是否已被拉黑.
     *
     * @return boolean
     * @author BS <414606094@qq.com>
     */
    public function hasDisabled($member = null): bool
    {
        if ($member === null) {
            $member = $this->getMember();
        }
        if ($member && $member->disabled === 1) {
            $this->error = '您已被该圈子拉黑';
            $this->status = 403;

            return true;
        }
        return false;
    }

    /**
     * 是否有发帖权限.
     *
     * @param $action
     * @param $member
     * @return boolean
     * @author BS <414606094@qq.com>
     */
    public function hasPostPermission($member = null): bool
    {
        if ($member && in_array($member->role, explode(',', $this->model->permissions))) {
            
            return true;
        }
        $this->error = '没有发帖权限';
        $this->status = 403;

        return false;
    }

    /**
     * 获取成员信息.
     *
     * @return Zhiyi\PlusGroup\Models\GroupMember
     * @author BS <414606094@qq.com>
     */
    public function getMember()
    {
        if (! isset($this->model) || ! isset($this->user)) {
            return false;
        }

        $key = sprintf($this->memberCacheKey, $this->model->id, $this->user->id);

        if (Cache::has($key)) {
            return Cache::get($key);
        }

        $member = $this->model->members()->where('audit', 1)->where('user_id', $this->user->id)->first();

        Cache::set($key, $member);

        return $member;
    }

    /**
     * 清理缓存.
     *
     * @param $key
     * @return void
     * @author BS <414606094@qq.com>
     */
    public function forget($key)
    {
        Cache::forget($key);

        return $this;
    }

    /**
     * 清理成员信息缓存
     *
     * @param $group 
     * @param $user 
     * @return void
     * @author BS <414606094@qq.com>
     */
    public function forgetMemberCache($group, $user)
    {
        if ($group instanceof GroupModel) {
            $group = $group->id;
        }

        if ($user instanceof UserModel) {
            $user = $user->id;
        }

        $key = sprintf($this->memberCacheKey, $group, $user);

        return $this->forget($key);
    }
}
