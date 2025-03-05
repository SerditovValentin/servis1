<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $table = 'post';
    public $timestamps = false;

    protected $fillable = ['post'];

    public function employee()
    {
        return $this->hasMany(Employee::class, 'id_post');
    }
}
