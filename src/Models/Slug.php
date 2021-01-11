<?php
namespace WanaKin\Slug\Models;

use Illuminate\Database\Eloquent\Model;

class Slug extends Model {
    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'slug'
    ];

    /**
     * Get the slugged model
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function sluggable() {
        return $this->morphTo();
    }
}
