<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;
    protected $table = 'client';
    public $timestamps = false;
    protected $fillable = ['surname', 'name', 'patronymic', 'phone', 'email'];

    public function appliances()
    {
        return $this->hasMany(Appliance::class, 'id_client');
    }

    public function repairRequests()
    {
        return $this->hasMany(RepairRequest::class, 'id_client');
    }
}

