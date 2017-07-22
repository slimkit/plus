<?php

namespace Zhiyi\Plus\Models;

use Illuminate\Database\Eloquent\Model;
use Zhiyi\Plus\Contracts\Model\ShouldAvatar as ShouldAvatarContract;

class CertificationCategory extends Model implements ShouldAvatarContract
{
    use Concerns\HasAvatar;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'name';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    // protected $appends = ['icon'];

    /**
     * avatar extensions.
     *
     * @var array
     */
    protected $avatar_extensions = ['png', 'jpeg', 'bmp'];

    /**
     * Avatar prefix.
     *
     * @var string
     */
    protected $avatar_prefix = 'certifications';

    /**
     * Get icon url.
     *
     * @return string|null
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getIconAttribute()
    {
        return $this->avatar();
    }

    /**
     * Get avatar trait.
     *
     * @return string|int
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getAvatarKey()
    {
        return $this->getKey();
    }
}
