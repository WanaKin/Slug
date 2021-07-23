<?php
namespace WanaKin\Slug\Facades;

use Illuminate\Support\Facades\Facade;

class SlugService extends Facade
{
    /**
     * Get the service name
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return \WanaKin\Slug\SlugService::class;
    }
}
