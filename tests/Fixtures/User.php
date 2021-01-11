<?php
namespace Tests\Fixtures;

use Illuminate\Foundation\Auth\User as Model;
use WanaKin\Slug\Sluggable;

class User extends Model {
    use Sluggable;

    /**
     * Properties that can't be mass assigned
     *
     * @var array
     */
    protected $guarded = [];
}
