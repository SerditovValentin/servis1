<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warranty extends Model
{
    use HasFactory;

    protected $table = 'warranty';
    public $timestamps = false;

    protected $fillable = ['id_repair_requests', 'warranty_period', 'warranty_start'];

    public function repairRequest()
    {
        return $this->belongsTo(RepairRequest::class, 'id_repair_requests');
    }
}