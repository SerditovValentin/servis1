<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;

    protected $table = 'warehouse';
    public $timestamps = false;

    protected $fillable = ['name', 'price', 'stock_quantity'];

    public function movements()
    {
        return $this->hasMany(WarehouseMovement::class, 'id_warehouse');
    }

    public function orderedParts()
    {
        return $this->hasMany(OrderedParts::class, 'id_warehouse');
    }
}

