<?php

declare(strict_types=1);

namespace SlimKit\Plus\Packages\Blog\Models;

use Illuminate\Database\Eloquent\Model;
use Zhiyi\Plus\FileStorage\Traits\EloquentAttributeTrait as FileStorageEloquentAttributeTrait;

class Blog extends Model
{
    use FileStorageEloquentAttributeTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'blogs';

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * The blog has many articles.
     */
    public function articles()
    {
        return $this->hasMany(Article::class, 'blog_id');
    }

    /**
     * Get the logo.
     * @param null|string $logo
     * @return null|mixed
     */
    protected function getLogoAttribute($logo)
    {
        if (! $logo) {
            return null;
        }

        return $this->getFileStorageResourceMeta($logo);
    }
}
