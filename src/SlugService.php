<?php
namespace WanaKin\Slug;

use Illuminate\Database\Eloquent\Model;
use WanaKin\Slug\Models\Slug;
use Illuminate\Support\Str;

class SlugService {
    /**
     * Get the slug for a model
     *
     * @param Model $model
     * @param ?string $default
     * @param bool $safety = TRUE
     * @return string
     */
    public function get( Model $model, ?string $default = NULL, bool $safety = TRUE ) : string {
        // Reload the relationship just in case
        $model->load( 'slug' );

        // Check if the model already has a slug
        if ( $slug = $model->slug ) {
            return $slug->slug;
        } else {
            // If not, use the provided default or generate a UUID
            if ( $default ) {
                $slug = $default;
                // If safety is enabled, add a random string to reduce the chance of a collision
                $slug .= $safety ? '-' . Str::random( 8 ) : NULL;
            } else {
                $slug = Str::uuid();
            }

            // Create a new slug record
            $model->slug()->create( [
                'slug' => $slug
            ] );

            return $slug;
        }
    }

    /**
     * Resolve a slug into a model
     *
     * @param string $slugStr
     * @return ?Model
     */
    public function resolve( string $slugStr ) : ?Model {
        // Get the Slug model
        if ( $slug = Slug::where( 'slug', $slugStr )->first() ) {
            // Return the sluggable model
            return $slug->sluggable;
        } else {
            return NULL;
        }
    }
}
