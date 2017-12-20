<?php

namespace SlimKit\PlusAroundAmap\Providers;

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
        // User::macro('around-amaps', function () {
        //     return $this->hasMany(\SlimKit\PlusAroundAmap\Models\around-amap::class);
        // });
    }

    /**
     * Register morph map for polymorphic relations.
     *
     * @return void
     */
    protected function registerMorphMap()
    {
        // $this->morphMap([
        //     'around-amaps' => \SlimKit\PlusAroundAmap\Models\around-amap::class,
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
