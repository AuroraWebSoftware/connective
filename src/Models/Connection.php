<?php

namespace AuroraWebSoftware\Connective\Models;

use AuroraWebSoftware\Connective\Contracts\ConnectiveContract;
use Illuminate\Database\Eloquent\Model;

class Connection extends Model
{
    protected $table = 'connective_connections';

    protected $guarded = [];

    public int $from_model_id;
    public string $from_model_type;

    public int $to_model_id;
    public string $to_model_type;

    public function connectedTo(): ConnectiveContract&Model
    {
        return $this->to_model_type::find($this->to_model_id);
    }

    public function connectedFrom(): ConnectiveContract&Model
    {
        return $this->from_model_type::find($this->from_model_id);
    }
}
