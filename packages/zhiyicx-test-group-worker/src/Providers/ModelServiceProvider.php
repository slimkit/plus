<?php

declare(strict_types=1);

namespace Zhiyi\Plus\Packages\TestGroupWorker\Providers;

use Zhiyi\Plus\Models\User;
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
        // User::macro('test-group-workers', function () {
        //     return $this->hasMany(\Zhiyi\Plus\Packages\TestGroupWorker\Models\test-group-worker::class);
        // });
        
        User::macro('githubAccess', function () {
            return $this->hasOne(\Zhiyi\Plus\Packages\TestGroupWorker\Models\Access::class, 'owner', 'id');
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
        //     'test-group-workers' => \Zhiyi\Plus\Packages\TestGroupWorker\Models\test-group-worker::class,
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
