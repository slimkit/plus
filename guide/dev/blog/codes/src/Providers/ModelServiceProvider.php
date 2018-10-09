<?php

declare(strict_types=1);

namespace SlimKit\Plus\Packages\Blog\Providers;

use Zhiyi\Plus\Models\User;
use Illuminate\Support\ServiceProvider;
use SlimKit\Plus\Packages\Blog\Models\Blog;
use Illuminate\Database\Eloquent\Relations\Relation;

class ModelServiceProvider extends ServiceProvider
{
    /**
     * Register the model service.
     *
     * @return void
     */
    public function register()
    {
        // Register morph map for polymorphic relations.
        $this->registerMorphMap();

        // Register user model macros tu the application.
        $this->registerUserMacros();
    }

    /**
     * Register user model macros tu the application.
     *
     * @return void
     */
    protected function registerUserMacros()
    {
        // The is define macro to User example.
        // User::macro('plus-blogs', function () {
        //     return $this->hasMany(\SlimKit\Plus\Packages\Blog\Models\plus-blog::class);
        // });
        User::macro('blog', function () {
            return $this->hasOne(Blog::class, 'owner_id');
        });
    }

    /**
     * Register morph map for polymorphic relations.
     *
     * @return void
     */
    protected function registerMorphMap()
    {
        // $this->morphMap([
        //     'plus-blogs' => \SlimKit\Plus\Packages\Blog\Models\plus-blog::class,
        // ]);
    }

    /**
     * Set or get the morph map for polymorphic relations.
     *
     * @param array|null $map
     * @param bool $merge
     * @return array
     */
    protected function morphMap(array $map = null, bool $merge = true): array
    {
        return Relation::morphMap($map, $merge);
    }
}
