<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeEquipment extends Model
{
    use HasFactory;

    protected $table = 'type_equipment';
    public $timestamps = false;

    protected $fillable = ['type'];

    public function appliance()
    {
        return $this->hasMany(Appliance::class, 'id_type_equipment');
    }
}
