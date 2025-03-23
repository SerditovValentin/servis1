<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderedParts extends Model
{
    use HasFactory;

    protected $table = 'ordered_parts';
    public $timestamps = false; 

    protected $fillable = ['id_order', 'id_warehouse', 'quantity'];

    public function order()
    {
        return $this->belongsTo(Order::class, 'id_order');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'id_warehouse');
    }
}
