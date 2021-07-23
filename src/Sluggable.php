<?php
namespace Wanakin\Slug;

use WanaKin\Slug\Facades\SlugService;
use WanaKin\Slug\Slug;
use Illuminate\Database\Eloquent\Model;

trait Sluggable
{
    /**
     * Get a model's slug
     */
    public function slug()
    {
        return $this->morphOne( Slug::class, 'sluggable' );
    }

    /**
     * Resolve a model
     *
     * @param string $slug The slug to resolve
     * @param mixed $field = null
     * @return ?Model
     */
    public function resolveRouteBinding($slug, $field = null)
    {
        return SlugService::resolve( $slug );
    }
}
