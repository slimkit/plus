<?php

declare(strict_types=1);

namespace SlimKit\Plus\Packages\Blog\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'blog_articles';
}
