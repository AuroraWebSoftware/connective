<?php

namespace AuroraWebSoftware\Connective\Collections;

use AuroraWebSoftware\Connective\Contracts\ConnectiveContract;
use Illuminate\Database\Eloquent\Collection;

class ConnectiveCollection extends Collection
{
    /**
     * @return ConnectiveCollection<int, ConnectiveContract>
     */
    public function connectivesOfEach(): ConnectiveCollection
    {

    }
}
