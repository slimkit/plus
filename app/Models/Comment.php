<?php

namespace Zhiyi\Plus\Models;

use Zhiyi\Plus\Models\User;
use Illuminate\Database\Eloquent\Model;
use Zhiyi\Plus\Contracts\Model\FetchComment;

class Comment extends Model implements FetchComment
{
    /**
     * User exposed observable events.
     *
     * These are extra user-defined events observers may subscribe to.
     *
     * @var array
     */
    protected $observables = ['fetch'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['comment_content', 'target_image', 'target_title', 'target_id'];

    /**
     * Fetch.
     *
     * @var [type]
     */
    protected $fetch;

    /**
     * The "booting" method of the model.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected static function boot()
    {
        parent::boot();

        // Register the comment observer.
        static::observe(\Zhiyi\Plus\Observers\CommentObserver::class);
    }

    /**
     * Has a user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Get comment centent.
     *
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getCommentContentAttribute(): string
    {
        if (($fetch = $this->fetch()) === null) {
            return '';
        }

        return $fetch->getCommentContentAttribute();
    }

    /**
     * Get target source title.
     *
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getTargetTitleAttribute(): string
    {
        if (($fetch = $this->fetch()) === null) {
            return '';
        }

        return $fetch->getTargetTitleAttribute();
    }

    /**
     * Get target source image file with id.
     *
     * @return int
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getTargetImageAttribute(): int
    {
        if (($fetch = $this->fetch()) === null) {
            return 0;
        }

        return $fetch->getTargetImageAttribute();
    }

    /**
     * Get target source id.
     *
     * @return int
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getTargetIdAttribute(): int
    {
        if (($fetch = $this->fetch()) === null) {
            return 0;
        }

        return $fetch->getTargetIdAttribute();
    }

    /**
     * Get fetch event.
     *
     * @return null|\Zhiyi\Plus\Contracts\Model\FetchComment
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function fetch()
    {
        if (is_null($this->fetch)) {
            $this->fetch = $this->fireModelEvent('fetch', true);

            if (! $this->fetch instanceof FetchComment && ! is_null($this->fetch)) {
                throw new \RuntimeException('Value must be an instance of '.FetchComment::class);
            }
        }

        return $this->fetch;
    }
}
