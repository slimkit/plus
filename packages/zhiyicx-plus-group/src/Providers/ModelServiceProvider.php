<?php

namespace Zhiyi\PlusGroup\Providers;

use Zhiyi\Plus\Models\User;
use Zhiyi\Plus\Models\Comment;
use Illuminate\Support\ServiceProvider;
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
        User::macro('groups', function () {
            return $this->hasMany(\Zhiyi\PlusGroup\Models\Group::class);
        });
    }

    /**
     * Register morph map for polymorphic relations.
     *
     * @return void
     */
    protected function registerMorphMap()
    {
        $this->morphMap([
            'groups' => \Zhiyi\PlusGroup\Models\Group::class,
        ]);

        $this->morphMap([
            'group-posts' => \Zhiyi\PlusGroup\Models\Post::class,
        ]);
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
