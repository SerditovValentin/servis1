<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'order';
    public $timestamps = false; 

    protected $fillable = ['id_supplier', 'total_amount', 'order_date', 'delivery_date', 'id_status'];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'id_supplier');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'id_status');
    }

    public function orderedParts()
    {
        return $this->belongsTo(OrderedParts::class, 'id_order');
    }
}
