<?php

namespace AuroraWebSoftware\Connective\Collections;

use AuroraWebSoftware\Connective\Contracts\ConnectiveContract;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class ConnectiveCollection extends Collection
{
    /**
     * @return ?ConnectiveCollection<ConnectiveContract>
     */
    public function connectives(string|array|null $connectionTypes = null, string|array|null $modelTypes = null): ?ConnectiveCollection
    {
        /**
         * @var ConnectiveCollection<ConnectiveContract> $collection;
         */
        $collection = ConnectiveCollection::make();
        $this->unique()->each(function (ConnectiveContract&Model $item) use ($collection, $connectionTypes, $modelTypes) {

            $item->connectives($connectionTypes, $modelTypes)
                ->unique()
                ->each(function (ConnectiveContract&Model $item2) use ($collection) {
                    $collection->push($item2);
                });
        });

        return $collection;
    }
}
