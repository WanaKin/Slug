<?php
namespace WanaKin\Slug;

use Illuminate\Database\Eloquent\Model;
use WanaKin\Slug\Models\Slug;
use Illuminate\Support\Str;

class SlugService {
    /**
     * Generate a random string
     *
     * @param int $size
     * @return string
     */
    private function random( int $size ) : string {
        // Get a more URL-friendly string than Str::random()
        return bin2hex( random_bytes( $size / 2 ) );
    }
    
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
                $slug .= $safety ? '-' . $this->random( 8 ) : NULL;
            } else {
                $slug = Str::uuid();
            }

            // Restore the slug if deleted
            if ( $model->slug()->withTrashed()->first() ) {
                $model->slug()->restore();

                // Update to the default value if set
                if ( $default ) {
                    $model->slug()->update( [
                        'slug' => $slug
                    ] );
                }

                // Update the slug
                $model->load( 'slug' );
                $slug = $model->slug->slug;
            } else {
                // Create a new slug record
                $model->slug()->create( [
                    'slug' => $slug
                ] );
            }

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

    /**
     * Delete a slug for a model or string
     *
     * @param string|Model $model
     * @pararm bool $forceDelete = FALSE
     * @return void
     */
    public function delete( $model, bool $forceDelete = FALSE ) : void {
        // If the model is a string, resolve to a Slug
        if ( is_string( $model ) ) {
            $slug = Slug::where( 'slug', $model )->first();
        } else if ( $model instanceof Model ) {
            $slug = $model->slug;
        }

        // If the slug was found, delete it
        if ( $slug ) {
            $forceDelete ? $slug->forceDelete() : $slug->delete();
        }
    }
}
