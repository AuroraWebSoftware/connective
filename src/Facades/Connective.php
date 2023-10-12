<?php

namespace AuroraWebSoftware\Connective\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static getConnectionTypes();
 *
 * @see \AuroraWebSoftware\Connective\ConnectiveService
 */
class Connective extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \AuroraWebSoftware\Connective\ConnectiveService::class;
    }
}
