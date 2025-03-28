<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appliance extends Model
{
    use HasFactory;

    protected $table = 'appliance';
    public $timestamps = false;

    protected $fillable = ['id_client', 'brand', 'model', 'id_type_equipment'];

    public function client()
    {
        return $this->belongsTo(Client::class, 'id_client');
    }

    public function type_equipment()
    {
        return $this->belongsTo(TypeEquipment::class, 'id_type_equipment');
    }
    public function repairRequests()
    {
        return $this->hasMany(RepairRequest::class, 'id_appliance');
    }
}
