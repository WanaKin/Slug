<?php
namespace WanaKin\Slug;

use Illuminate\Support\ServiceProvider;

class SlugServiceProvider extends ServiceProvider {
    /**
     * Register application services
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap package services
     *
     * @return void
     */
    public function boot()
    {
        // Backwards compatibility
        if (!class_exists('\\WanaKin\\Slug\\Models\\Slug')) {
            class_alias('\\WanaKin\\Slug\\Slug', '\\WanaKin\\Slug\\Models\\Slug');
        }

        // Load migrations
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }
}
