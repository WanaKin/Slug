<?php
namespace Wanakin\Slug;

use WanaKin\Slug\Facades\SlugService;
use WanaKin\Slug\Models\Slug;
use Illuminate\Database\Eloquent\Model;

trait Sluggable {
    /**
     * Get a model's slug
     */
    public function slug() {
        return $this->morphOne( Slug::class, 'sluggable' );
    }

    /**
     * Resolve a model
     *
     * @param string $slug
     * @param mixed $field = NULL
     * @return ?Model
     */
    public function resolveRouteBinding( $slug, $field = NULL ) : ?Model {
        return SlugService::resolve( $slug );
    }
}
