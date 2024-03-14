<?php

namespace Jgu\Wfotp\Facades;

use Illuminate\Support\Facades\Facade;

class Wfo extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'Wfo';
    }
}