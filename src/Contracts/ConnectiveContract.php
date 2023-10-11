<?php

namespace AuroraWebSoftware\Connective\Contracts;

use AuroraWebSoftware\Connective\Collections\ConnectiveCollection;
use AuroraWebSoftware\Connective\Models\Relation;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface ConnectiveContract
{
    /**
     * @return array<string>
     */
    public static function supportedEdgeTypes(): array;

    /**
     * @param Model&ConnectiveContract $model
     * @param string $type
     * @return bool
     */
    public function relateTo(ConnectiveContract & Model $model, string $type): bool;


    /**
     * @param string|array<string> $edgeTypes
     * @return ConnectiveCollection<ConnectiveContract>
     */
    public function connectives(string|array $edgeTypes, string|array $modelTypes): ConnectiveCollection;

    /**
     * @param string|array $edgeTypes
     * @param string|array $modelTypes
     * @return Collection<int, Relation>
     */
    public function relations(string|array $edgeTypes, string|array $modelTypes): Collection;

    public function hasRelation(string $edgeTypes, string $modelTypes): bool;

}