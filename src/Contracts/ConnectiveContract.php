<?php

namespace AuroraWebSoftware\Connective\Contracts;

use AuroraWebSoftware\Connective\Collections\ConnectiveCollection;
use AuroraWebSoftware\Connective\Exceptions\ConnectionTypeException;
use AuroraWebSoftware\Connective\Exceptions\ConnectionTypeNotSupportedException;
use AuroraWebSoftware\Connective\Models\Connection;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface ConnectiveContract
{
    /**
     * @return array<string>
     */
    public static function supportedConnectionTypes(): array;

    public function getId(): int|string;

    /**
     * Connect and return the connection model
     *
     * @throws ConnectionTypeException
     * @throws ConnectionTypeNotSupportedException
     */
    public function connectTo(ConnectiveContract&Model $model, string $connectionType): Connection;

    /**
     * returns connection model instances as a collection
     *
     * @param  string|array<class-string>  $modelTypes
     * @return Collection<Connection>|null
     */
    public function connections(string|array $connectionTypes = null, string|array $modelTypes = null): ?Collection;

    /**
     * returns connected model instances (connective models) as a collection
     *
     * @param  string|array<class-string>  $modelTypes
     * @return ConnectiveCollection<int, ConnectiveContract>|null
     */
    public function connectives(string|array $connectionTypes, string|array $modelTypes): ?ConnectiveCollection;

    /**
     * returns connection model instances as a collection
     *
     * @param  string|array<class-string>  $modelTypes
     * @return Collection<Connection>|null
     */
    public function inverseConnections(string|array $connectionTypes = null, string|array $modelTypes = null): ?Collection;

    /**
     * returns connected model instances (connective models) as a collection
     *
     * @param  string|array<class-string>  $modelTypes
     * @return ConnectiveCollection<int, ConnectiveContract>|null
     */
    public function inverseConnectives(string|array $connectionTypes, string|array $modelTypes): ?ConnectiveCollection;
}
