<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovementType extends Model
{
    use HasFactory;

    protected $table = 'movement_type';
    public $timestamps = false;

    protected $fillable = ['type'];

    public function employees()
    {
        return $this->hasMany(WarehouseMovement::class, 'id_movement_type');
    }

    public function accounting()
    {
        return $this->hasMany(Accounting::class, 'id_movement_type');
    }
}
