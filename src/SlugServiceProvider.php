<?php
namespace WanaKin\Slug;

use Illuminate\Support\ServiceProvider;

class SlugServiceProvider extends ServiceProvider {
    /**
     * Register application services
     *
     * @return void
     */
    public function register() {

    }

    /**
     * Bootstrap package services
     *
     * @return void
     */
    public function boot() {
        // Load migrations
        $this->loadMigrationsFrom( __DIR__ . '/../database/migrations' );
    }
}
