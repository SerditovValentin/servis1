<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Repair extends Model
{
    use HasFactory;

    protected $table = 'repair';
    public $timestamps = false;

    protected $fillable = ['id_repair_requests', 'repair_details', 'repair_date_time', 'id_status'];

    public function repairRequest()
    {
        return $this->belongsTo(RepairRequest::class, 'id_repair_requests');
    }

    public function istatus()
    {
        return $this->belongsTo(Status::class, 'id_status');
    }
}
