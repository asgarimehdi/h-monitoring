<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    // رابطه‌ی چند به چند با کاربران
    public function users()
    {
        return $this->belongsToMany(User::class, 'role_user');
    }
}