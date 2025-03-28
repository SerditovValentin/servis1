<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    protected $table = 'status';
    public $timestamps = false;
    
    protected $fillable = ['status'];

    public function repair_request()
    {
        return $this->hasMany(RepairRequest::class, 'id_status');
    }

    public function repair()
    {
        return $this->hasMany(Repair::class, 'id_status');
    }

    public function order()
    {
        return $this->hasMany(Order::class, 'id_status');
    }
    
    public function repairParts()
    {
        return $this->hasMany(RepairParts::class, 'id_status');
    }
}
