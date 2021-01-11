<?php
namespace Tests\Feature;

use WanaKin\Slug\Facades\SlugService;
use Tests\Fixtures\User;
use Illuminate\Foundation\Testing\WithFaker;

class SlugServiceTest extends FeatureTestCase {
    use WithFaker;
    
    /**
     * Test creating a slug for a post
     *
     * @return void
     */
    public function testGet() {
        $model = $this->createSluggable();
        
        // Test creating a slug for a model
        $slug = SlugService::get( $model );

        // Assert the database was updated
        $this->assertDatabaseHas( 'slugs', [
            'sluggable_type' => $model::class,
            'sluggable_id' => $model->id,
            'slug' => $slug
        ] );

        // Assert that the next request returns the same slug
        $this->assertEquals( $slug, SlugService::get( $model ) );
    }

    /**
     * Test creating a slug with a default
     *
     * @return void
     */
    public function testGetWithDefault() {
        $model = $this->createSluggable();;
        
        // Test creating a slug for a post with a default
        $slugBase = $this->faker->word;

        // Assert the correct slug was returned
        $slug = SlugService::get( $model, $slugBase );
        $this->assertEquals( $slugBase, substr( $slug, 0, strlen( $slugBase ) ) );

        // Assert the datatbase was updated
        $this->assertDatabaseHas( 'slugs', [
            'sluggable_type' => $model::class,
            'sluggable_id' => $model->id,
            'slug' => $slug
        ] );
    }

    /**
     * Test creating a slug with a default and no safety
     *
     * @return void
     */
    public function testGetWithDefaultAndNoSafety() {
        $model = $this->createSluggable();
        
        // Test creating a slug for a post with a default
        $slug = $this->faker->word;

        // Assert the correct slug was returned
        $this->assertEquals( $slug, SlugService::get( $model, $slug, FALSE ) );

        // Assert the datatbase was updated
        $this->assertDatabaseHas( 'slugs', [
            'sluggable_type' => $model::class,
            'sluggable_id' => $model->id,
            'slug' => $slug
        ] );
    }

    /**
     * Test resolving a slug
     *
     * @return void
     */
    public function testResolve() {
        $model = $this->createSluggable();
        
        // Create a slug
        $slug = 'YOLO';
        $model->slug()->create( [
            'slug' => $slug
        ] );

        // Resolve the slug
        $resolved = SlugService::resolve( $slug );

        // Assert a post was returned
        $this->assertInstanceOf( $model::class, $resolved );

        // Assert the correct post was returned
        $this->assertEquals( $model->id, $resolved->id );
    }
}
