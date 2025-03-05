<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class Employee extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $table = 'employee';
    public $timestamps = false;

    protected $fillable = [
        'surname',
        'name',
        'patronymic',
        'date_of_birth',
        'id_post',
        'phone_number',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Метод для получения пароля
    public function getAuthPassword()
    {
        return $this->password;
    }

    public function post()
    {
        return $this->belongsTo(Post::class, 'id_post');
    }
}
