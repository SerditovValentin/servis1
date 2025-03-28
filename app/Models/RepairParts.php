<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepairParts extends Model
{
    use HasFactory;
    protected $table = 'repair_parts';
    public $timestamps = false;
    protected $fillable = ['id_repair', 'id_warehouse', 'id_status',];

    public function repair()
    {
        return $this->belongsTo(Repair::class, 'id_repair');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'id_warehouse');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'id_status');
    }
}
