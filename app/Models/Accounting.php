<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Accounting extends Model
{
    use HasFactory;
    protected $table = 'accounting';
    public $timestamps = false;
    protected $fillable = ['id_repair_requests', 'id_movement_type', 'amount', 'transaction_date'];

    public function repairRequest()
    {
        return $this->belongsTo(RepairRequest::class, 'id_repair_requests');
    }

    public function movementType()
    {
        return $this->belongsTo(MovementType::class, 'id_movement_type');
    }
}
