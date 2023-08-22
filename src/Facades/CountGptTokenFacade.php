<?php

namespace ZohaibTariq\CountGptToken\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \ZohaibTariq\CountGptToken\CountGptToken
 */
class CountGptTokenFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'count-gpt-token';
    }
}
