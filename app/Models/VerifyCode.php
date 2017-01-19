<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VerifyCode extends Model
{
    use SoftDeletes;

    /**
     * 设置data字段，并将其格式化.
     *
     * @param any $data 数据
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function setDataAttribute($data)
    {
        $this->attributes['data'] = serialize($data);
    }

    /**
     * 获取data字段，将其反序列化.
     *
     * @param string $data 需要被反序列化的数据
     *
     * @return any 任意格式
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function getDataAttribute(string $data)
    {
        return unserialize($data);
    }

    /**
     * 复用的设置查询账户方法.
     *
     * @param Builder $query   查询对象
     * @param string  $account 账户
     *
     * @return Builder 查询对象
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function scopeByAccount(Builder $query, string $account): Builder
    {
        return $query->where('account', $account);
    }

    /**
     * 复用的设置code查询条件方法.
     *
     * @param Builder $query 查询对象
     * @param int     $code  验证码
     *
     * @return Builder 查询对象
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function scopeByCode(Builder $query, int $code): Builder
    {
        return $query->where('code', $code);
    }

    /**
     * 设置倒叙查询.
     *
     * @param Builder $query 查询对象
     *
     * @return Builder 查询对象
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function scopeOrderByDesc(Builder $query): Builder
    {
        return $query->orderBy('id', 'desc');
    }

    /**
     * 设置复用的创建时间范围查询，单位秒.
     *
     * @param Builder $query  查询对象
     * @param int     $second 范围时间，单位秒
     *
     * @return Builder 查询对象
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function scopeByValid(Builder $query, int $second = 300): Builder
    {
        $now = $this->freshTimestamp();
        $sub = clone $now;
        $sub->subSeconds($second);

        return $query->whereBetween('created_at', [$sub, $now]);
    }

    /**
     * 计算距离验证码过期时间.
     *
     * @param int $vaildSecond 验证的总时间
     *
     * @return int 剩余时间
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function makeSurplusSecond(int $vaildSecond = 60): int
    {
        $now = $this->freshTimestamp();
        $differ = $this->created_at->diffInSeconds($now);

        return $vaildSecond - $differ;
    }

    /**
     * 生成验证码
     *
     * @param int $min 最小数
     * @param int $max 最大数
     *
     * @return self 自身对象
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function makeVerifyCode(int $min = 1000, int $max = 9999): self
    {
        $min = min($min, $max);
        $max = max($min, $max);

        if (function_exists('mt_rand')) {
            $this->attributes['code'] = mt_rand($min, $max);
            // 测试后门
            $this->attributes['code'] = 1234;

            return $this;
        }

        $this->attributes['code'] = rand($min, $max);
        // 测试后门
        $this->attributes['code'] = 1234;

        return $this;
    }
}
