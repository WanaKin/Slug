<?php
namespace Tests\Feature;

use WanaKin\Slug\SlugServiceProvider;
use Orchestra\Testbench\TestCase;
use Tests\Fixtures\User;

class FeatureTestCase extends TestCase
{
    /**
     * Set up before each test
     *
     * @return void
     */
    public function setUp() : void
    {
        parent::setUp();

        // Load and run migrations
        $this->loadLaravelMigrations();
        $this->artisan( 'migrate' )->run();
    }

    /**
     * Create a sluggable user
     *
     * @return void
     */
    public function createSluggable()
    {
        return User::create([
            'email' => 'test@example.com',
            'name' => 'Wana Kin',
            'password' => '$2y$10$obP//QcLS4VgeDgkIesqluxNwz78nNOM9keum3BUR1yDHoyUlcG1m'
        ]);
    }

    /**
     * Load the package provider
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [SlugServiceProvider::class];
    }
}
