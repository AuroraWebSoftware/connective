<?php

namespace AuroraWebSoftware\Connective;

use AuroraWebSoftware\Connective\Exceptions\ConfigValueException;
use Illuminate\Support\Facades\Config;

class ConnectiveService
{

    /**
     * @return array<string>
     * @throws ConfigValueException
     */
    public function getConnectionTypes(): array
    {
        return is_array(Config::get('connective.connection_types')) ?
            Config::get('connective.connection_types') :
            throw new ConfigValueException();
    }

}
