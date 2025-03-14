<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepairRequest extends Model
{
    use HasFactory;

    protected $table = 'repair_requests';
    public $timestamps = false;

    protected $fillable = ['id_client', 'id_appliance', 'issue_description', 'address', 'preferred_visit_time', 'id_status'];

    public function client()
    {
        return $this->belongsTo(Client::class, 'id_client');
    }

    public function appliance()
    {
        return $this->belongsTo(Appliance::class, 'id_appliance');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'id_status');
    }
}
