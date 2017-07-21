<?php

namespace Zhiyi\Plus\Models;

use Illuminate\Database\Eloquent\Model;
use Zhiyi\Plus\Contracts\Model\ShouldAvatar as ShouldAvatarContract;

class Certification extends Model implements ShouldAvatarContract
{
    use Concerns\HasAvatar;

    protected $hidden = [
        'created_at',
        'updated_at',
        'user_id',
    ];

    public $incrementing = false;

    protected $primaryKey = 'name';

    /**
     * avatar extensions.
     *
     * @var array
     */
    protected $avatar_extensions = ['png', 'jpeg', 'jpg'];

    /**
     * Avatar prefix.
     *
     * @var string
     */
    protected $avatar_prefix = 'certification_icons';


    protected $appends = ['icon'];

    public function getAvatarKey()
    {
        return $this->getKey();
    }

    public function getIconAttribute()
    {
        return $this->avatar();
    }

    //
    public function userCertifications()
    {
        return $this->hasMany(UserCertification::class, 'Certification');
    }
}
