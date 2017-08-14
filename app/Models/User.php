<?php

namespace Zhiyi\Plus\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Zhiyi\Plus\Http\Controllers\APIs\V2\UserAvatarController;
use Zhiyi\Plus\Contracts\Model\ShouldAvatar as ShouldAvatarContract;

class User extends Authenticatable implements ShouldAvatarContract
{
    // 功能性辅助相关。
    use Notifiable,
        SoftDeletes,
        Concerns\HasAvatar,
        Concerns\UserHasNotifiable,
        Concerns\Macroable;
    // 关系数据相关
    use Relations\UserHasWallet,
        Relations\UserHasWalletCash,
        Relations\UserHasWalletCharge,
        Relations\UserHasFilesWith,
        Relations\UserHasFollow,
        Relations\UserHasComment,
        Relations\UserHasReward,
        Relations\UserHasLike;
    // 解决冲突
    use Relations\UserHasRolePerms {
        SoftDeletes::restore insteadof Relations\UserHasRolePerms;
        Relations\UserHasRolePerms::restore insteadof SoftDeletes;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'phone', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'phone', 'email', 'deleted_at', 'pivot',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['avatar', 'bg', 'verified'];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['extra'];

    /**
     * Get avatar key.
     *
     * @return int
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getAvatarKey()
    {
        return $this->getKey();
    }

    /**
     * Get avatar attribute.
     *
     * @return string|null
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getAvatarAttribute()
    {
        if (! $this->avatarPath()) {
            return null;
        }

        return action('\\'.UserAvatarController::class.'@show', ['user' => $this]);
    }

    /**
     * Get user background image.
     *
     * @return string|null
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getBgAttribute()
    {
        return $this->avatar(0, 'user-bg');
    }

    /**
     * Get verifed.
     *
     * @return array|null
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getVerifiedAttribute()
    {
        $certification = $this->certification()
            ->where('status', 1)
            ->first();

        if (! $certification) {
            return null;
        }

        return [
            'type' => $certification->certification_name,
            'icon' => $certification->icon,
        ];
    }

    /**
     * Has user extra.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function extra()
    {
        return $this->hasOne(UserExtra::class, 'user_id', 'id');
    }

    /**
     * Has user certification.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function certification()
    {
        return $this->hasOne(Certification::class, 'user_id', 'id');
    }

    /**
     * Has tags of the user.
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
     * 复用设置手机号查询条件方法.
     *
     * @param Illuminate\Database\Eloquent\Builder $query 查询对象
     * @param string  $phone 手机号码
     *
     * @return Illuminate\Database\Eloquent\Builder 查询对象
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function scopeByPhone(Builder $query, string $phone): Builder
    {
        return $query->where('phone', $phone);
    }

    /**
     * 复用设置用户名查询条件方法.
     *
     * @param Illuminate\Database\Eloquent\Builder $query 查询对象
     * @param string  $name  用户名
     *
     * @return Illuminate\Database\Eloquent\Builder 查询对象
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function scopeByName(Builder $query, string $name): Builder
    {
        return $query->where('name', $name);
    }

    /**
     * 复用 E-Mail 查询条件方法.
     *
     * @param Illuminate\Database\Eloquent\Builder $query
     * @param string $email [description]
     * @return Illuminate\Database\Eloquent\Builder
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function scopeByEmail(Builder $query, string $email): Builder
    {
        return $query->where('email', $email);
    }

    /**
     * Create user ppassword.
     *
     * @param string $password user password
     *
     * @return self
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function createPassword(string $password): self
    {
        $this->password = app('hash')->make($password);

        return $this;
    }

    /**
     * 验证用户密码
     *
     * @Author   Wayne[qiaobin@zhiyicx.com]
     * @DateTime 2016-12-30T18:44:40+0800
     *
     * @param string $password [description]
     *
     * @return bool 验证结果true or false
     */
    public function verifyPassword(string $password): bool
    {
        return $this->password && app('hash')->check($password, $this->password);
    }

    /**
     * 用户tokens�
     * �系.
     *
     * @Author   Wayne[qiaobin@zhiyicx.com]
     * @DateTime 2017-01-03T10:13:06+0800
     *
     * @return object \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tokens()
    {
        return $this->hasMany(AuthToken::class, 'user_id');
    }

    /**
     * 用户资料.
     *
     * @return [type] [description]
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function datas()
    {
        $table = app(UserProfileSettingLink::class)->getTable();

        return $this->belongsToMany(UserProfileSetting::class, $table, 'user_id', 'user_profile_setting_id')
            ->withPivot('user_profile_setting_data', 'user_id')
            ->withTimestamps();
    }

    /**
     * 更新用户资料.
     *
     * @param array $attributes 更新�
     * �联profile资料数据
     *                          参考：https://laravel-china.org/docs/5.3/eloquent-relationships#updating-many-to-many-relationships
     *
     * @return [type] [description]
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function syncData(array $attributes)
    {
        if (! $attributes) {
            return false;
        }

        foreach ($attributes as &$value) {
            $value = [
                'user_profile_setting_data' => $value,
            ];
        }

        return $this->datas()->sync($attributes, false);
    }
}
