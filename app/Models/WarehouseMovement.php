<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseMovement extends Model
{
    use HasFactory;

    protected $table = 'warehouse_movements';
    public $timestamps = false;

    protected $fillable = ['id_warehouse', 'quantity', 'id_movement_type', 'movement_date'];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'id_warehouse');
    }

    public function movement_type()
    {
        return $this->belongsTo(MovementType::class, 'id_movement_type');
    }
}
