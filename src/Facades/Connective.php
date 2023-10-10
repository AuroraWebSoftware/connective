<?php

namespace AuroraWebSoftware\Connective\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \AuroraWebSoftware\Connective\Connective
 */
class Connective extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \AuroraWebSoftware\Connective\Connective::class;
    }
}
