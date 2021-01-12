<?php
namespace WanaKin\Slug;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Slug extends Model {
    use SoftDeletes;
    
    /**
     * Disable timestampts
     *
     * @var bool
     */
    public $timestamps = FALSE;
    
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
