<?php
namespace WanaKin\Slug;

use Illuminate\Database\Eloquent\Model;
use WanaKin\Slug\Models\Slug;
use Illuminate\Support\Str;

class SlugService
{
    /**
     * Generate a random string
     *
     * @param int $size The size of the string
     * @return string
     */
    protected function random($size)
    {
        return Str::lower(Str::random($size));
    }

    /**
     * Get the slug for a model
     *
     * @param Model $model The model to fetch the slug for
     * @param ?string $default If a slug doesn't already exist for the model, the default slug to set
     * @param bool $safety = true Whether or not to add a short random string to the end of the slug to reduce the chance of collisions
     * @return string
     */
    public function get($model, $default = null, $safety = true)
    {
        // Reload the relationship just in case
        $model->load('slug');

        // Check if the model already has a slug
        if ($slug = $model->slug) {
            return $slug->slug;
        } else {
            // If not, use the provided default or generate a UUID
            if ($default) {
                $slug = $default;
                // If safety is enabled, add a random string to reduce the chance of a collision
                $slug .= $safety ? '-' . $this->random(8) : NULL;
            } else {
                // Fallback to a UUID
                $slug = Str::uuid();
            }

            // Restore the slug if deleted
            if ($model->slug()->withTrashed()->first()) {
                // Restore
                $model->slug()->restore();

                // Update to the default value if set
                if ($default) {
                    $model->slug()->update([
                        'slug' => $slug
                    ]);
                }

                // Update the slug
                $model->load('slug');
                $slug = $model->slug->slug;
            } else {
                // Create a new slug record
                $model->slug()->create([
                    'slug' => $slug
                ]);
            }

            // Return the model's slug
            return $slug;
        }
    }

    /**
     * Resolve a slug into a model
     *
     * @param string $slugStr The slug to resolve
     * @return ?Model The resolved model or null if none found
     */
    public function resolve($slugStr) {
        // Get the Slug model
        if ($slug = Slug::where('slug', $slugStr)->first()) {
            // Return the sluggable model
            return $slug->sluggable;
        } else {
            return null;
        }
    }

    /**
     * Delete a slug for a model or string
     *
     * @param string|Model $model The slug or model whose slug to delete
     * @pararm bool $forceDelete = false Set to true to force delete the slug instead of a soft delete
     * @return void
     */
    public function delete($model, $forceDelete = false) {
        // If the model is a string, resolve to a Slug model
        if (is_string($model)) {
            $slug = Slug::where('slug', $model)->first();
        } else if ( $model instanceof Model ) {
            // If a model is passed, get the model's slug
            $slug = $model->slug;
        }

        // If the slug was found, (soft) delete it
        if ($slug) {
            $forceDelete ? $slug->forceDelete() : $slug->delete();
        }
    }
}
