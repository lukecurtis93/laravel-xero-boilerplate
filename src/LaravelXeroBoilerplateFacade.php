<?php

namespace Lukecurtis\LaravelXeroBoilerplate;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Lukecurtis\LaravelXeroBoilerplate\Skeleton\SkeletonClass
 */
class LaravelXeroBoilerplateFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-xero-boilerplate';
    }
}
