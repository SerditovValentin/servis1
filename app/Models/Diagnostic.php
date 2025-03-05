<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diagnostic extends Model
{
    use HasFactory;

    protected $table = 'diagnostic';
    public $timestamps = false;

    protected $fillable = ['id_repair_requests', 'id_employee', 'diagnosis', 'estimated_cost', 'confirmation_status'];

    public function repairRequest()
    {
        return $this->belongsTo(RepairRequest::class, 'id_repair_requests');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'id_employee');
    }
}
