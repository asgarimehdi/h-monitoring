<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    protected $fillable = [
        'user_id',
        'organizational_unit_id',
        'first_name',
        'last_name',
        'position',
    ];

    // هر person متعلق به یک user است
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // هر person می‌تواند به یک واحد سازمانی تعلق داشته باشد
    public function organizationalUnit()
    {
        return $this->belongsTo(OrganizationalUnit::class);
    }
}