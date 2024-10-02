<?php

namespace Wovosoft\LaravelCpanel\Facades;

use Illuminate\Support\Facades\Facade;

class LaravelCpanel extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'laravel-cpanel';
    }
}
