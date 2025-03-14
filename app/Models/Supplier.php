<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'supplier';
    public $timestamps = false;

    protected $fillable = ['name', 'inn', 'bank', 'bik', 'account_number', 'director_name', 'accountant_name'];

    public function orders()
    {
        return $this->hasMany(Order::class, 'id_supplier');
    }

    public function warehouse()
    {
        return $this->hasMany(Warehouse::class, 'id_supplier');
    }

}
