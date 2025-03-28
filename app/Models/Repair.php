<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Repair extends Model
{
    use HasFactory;

    protected $table = 'repair';
    public $timestamps = false;

    protected $fillable = ['id_repair_requests', 'id_employee', 'repair_details', 'repair_date_time',];

    public function repairRequest()
    {
        return $this->belongsTo(RepairRequest::class, 'id_repair_requests');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'id_employee');
    }

    public function repairParts()
    {
        return $this->hasMany(RepairParts::class, 'id_repair');
    }
   
}
