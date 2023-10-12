<?php

namespace AuroraWebSoftware\Connective\Traits;

use AuroraWebSoftware\Connective\Collections\ConnectiveCollection;
use AuroraWebSoftware\Connective\Contracts\ConnectiveContract;
use AuroraWebSoftware\Connective\Exceptions\ConnectionTypeException;
use AuroraWebSoftware\Connective\Models\Connection;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

trait Connective
{
    /**
     * @throws ConnectionTypeException
     */
    public function connectTo(ConnectiveContract&Model $model, string $type): bool
    {

        if (! in_array($type, \AuroraWebSoftware\Connective\Facades\Connective::getConnectionTypes())) {
            throw new ConnectionTypeException("$type not found");
        }

        return Connection::firstOrCreate(
            [
                'from_model_type' => get_class($this),
                'from_model_id' => $this->id,
                'to_model_type' => get_class($model),
                'to_model_id' => $model->id,
                'connection_type' => $type,
            ]
        );
    }

    /**
     * returns connection model instances as a collection
     *
     * @param  string|array<class-string>  $modelTypes
     * @return Collection<Connection>|null
     */
    public function connections(string|array $connectionTypes = null, string|array $modelTypes = null): ?Collection
    {
        $query = Connection::query();

        if ($connectionTypes !== null) {
            $connectionTypes = is_array($connectionTypes) ? $connectionTypes : [$connectionTypes];
            $query->whereIn('connection_type', $connectionTypes);
        }

        if ($modelTypes !== null) {
            $modelTypes = is_array($modelTypes) ? $modelTypes : [$modelTypes];
            $query->whereIn('to_model_type', $modelTypes);
        }

        $query->where('from_model_type', get_class($this))
            ->where('from_model_id', $this->id);

        return $query->get();
    }

    /**
     * @return ConnectiveCollection<ConnectiveContract>|null
     */
    public function connectives(string|array $connectionTypes = null, string|array $modelTypes = null): ?ConnectiveCollection
    {
        $connections = $this->connections($connectionTypes, $modelTypes);
        $collection = ConnectiveCollection::make();

        foreach ($connections as $connection) {
            $toModelType = $connection->to_model_type;
            $toModelId = $connection->to_model_id;

            $toModelInstance = $toModelType::find($toModelId);
            $collection->push($toModelInstance);
        }

        return $collection;
    }
}
