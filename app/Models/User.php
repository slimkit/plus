<?php

namespace Zhiyi\Plus\Models;

use Zhiyi\Plus\Traits\UserRolePerms;
use Zhiyi\Plus\Traits\Model\UserFollw;
use Zhiyi\Plus\Traits\Model\UserWallet;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable,
        SoftDeletes, // 软删除
        UserWallet, // 用户钱包
        Concerns\UserWalletCash, // 钱包提现
        Concerns\UserWalletCharge, // 钱包凭据
        UserFollw; // 用户关注
    use UserRolePerms {
        SoftDeletes::restore insteadof UserRolePerms;
        UserRolePerms::restore insteadof SoftDeletes;
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
        'password', 'remember_token',
    ];

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
        return $query->where('phone', 'LIKE', $phone);
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
        return $query->where('name', 'LIKE', $name);
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
        return $query->where('email', 'LIKE', $email);
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
        return app('hash')->check($password, $this->password);
    }

    /**
     * 用户登录记录关系.
     *
     * @Author   Wayne[qiaobin@zhiyicx.com]
     * @DateTime 2016-12-30T18:47:51+0800
     *
     * @return object \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function loginRecords()
    {
        return $this->hasMany(loginRecord::class, 'user_id');
    }

    /**
     * 用户tokens关系.
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

    public function groups()
    {
        return $this->hasMany(UserGroupLink::class, 'user_id');
    }

    /**
     * 用户附件.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function storages()
    {
        $table = app(StorageUserLink::class)->getTable();

        return $this->belongsToMany(Storage::class, $table, 'user_id', 'storage_id')
            ->withTimestamps();
    }

    /**
     * 用户附件关系.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function storagesLinks()
    {
        return $this->hasMany(StorageUserLink::class, 'user_id');
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
     * 用户拥有多条统计数据.
     *
     * @author bs<414606094@qq.com>
     *
     * @return [type] [description]
     */
    public function counts()
    {
        return $this->hasMany(UserDatas::class, 'user_id');
    }

    /**
     * 更新用户资料.
     *
     * @param array $attributes 更新关联profile资料数据
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

    /**
     * 我关注的用户.
     *
     * @return [type] [description]
     */
    public function follows()
    {
        return $this->hasMany(Following::class, 'user_id');
    }

    /**
     * 关注我的用户.
     *
     * @return [type] [description]
     */
    public function followeds()
    {
        return $this->hasMany(Followed::class, 'user_id');
    }
}
