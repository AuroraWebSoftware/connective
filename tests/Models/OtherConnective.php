<?php

namespace AuroraWebSoftware\Connective\Tests\Models;

use AuroraWebSoftware\Connective\Contracts\ConnectiveContract;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $name
 */
class OtherConnective extends Model implements ConnectiveContract
{
    use \AuroraWebSoftware\Connective\Traits\Connective;

    protected $guarded = [];

    /**
     * {@inheritDoc}
     */
    public static function supportedConnectionTypes(): array
    {
        return ['c'];
    }
}
