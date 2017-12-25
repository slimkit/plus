<?php

namespace Zhiyi\PlusGroup\Models;

use Zhiyi\Plus\Models\Tag;
use Zhiyi\Plus\Models\User;
use Zhiyi\Plus\Models\Report;
use Illuminate\Database\Eloquent\Model;
use Zhiyi\Plus\Models\Concerns\HasAvatar;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model
{
    use HasAvatar, SoftDeletes;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'groups';

    protected $appends = ['avatar'];

    /**
     * Get avatar trait.
     *
     * @return string|int
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getAvatarKey(): string
    {
        return $this->getKey();
    }

    /**
     * Avatar prefix.
     *
     * @return string
     * @author BS <414606094@qq.com>
     */
    public function getAvatarPrefix(): string
    {
        return 'group/avatars';
    }

    /**
     * Get avatar attribute.
     *
     * @return string|null
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getAvatarAttribute()
    {
        return $this->avatar();
    }

    /**
     * The tags of the group
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable', 'taggables')
            ->withTimestamps();
    }

    /**
     * The members of the group.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function members()
    {
        return $this->hasMany(GroupMember::class, 'group_id', 'id');
    }

    /**
     * Incomes of the group.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * @author BS <414606094@qq.com>
     */
    public function incomes()
    {
        return $this->hasMany(GroupIncome::class, 'group_id', 'id');
    }

    /**
     * 用户是否加入.
     *
     * @param $user
     */
    public function joined($userId, $groupId)
    {
        return  (bool) GroupMember::where('user_id', $userId)->where('group_id', $groupId)->count();
    }

    /**
     * 圈子创建者（申请者）.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     * @author BS <414606094@qq.com>
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * 圈主（圈子的创建者不一定是当前圈主、存在转让圈主这一操作）.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     * @author BS <414606094@qq.com>
     */
    public function founder()
    {
        return $this->hasOne(GroupMember::class, 'group_id', 'id')->where('role', 'founder');
    }

    /**
     * 圈子帖子
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     * @author BS <414606094@qq.com>
     */
    public function posts()
    {
        return $this->hasMany(Post::class, 'group_id', 'id');
    }
    
    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }

    public function recommend()
    {
        return $this->belongsTo(GroupRecommend::class, 'id', 'group_id');
    }

    /**
     * 被举报记录.
     *
     * @return morphMany
     * @author BS <414606094@qq.com>
     */
    public function reports()
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    /**
     * 用户在当前圈子是否有管理权限.
     *
     * @param $user
     * @return boolean
     * @author BS <414606094@qq.com>
     */
    public function isManager($user)
    {
        if ($user instanceof User) {
            $user = $user->id;
        }

        return (bool) $this->members()->where('user_id', $user)->whereIn('role', ['administrator', 'founder'])->count();
    }
}
