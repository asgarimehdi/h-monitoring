<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganizationalUnit extends Model
{
    protected $fillable = [
        'name',
        'description',
        'province_id',
        'county_id',
        'parent_id',
        'unit_type_id',

    ];
    public function unitType()
    {
        return $this->belongsTo(UnitType::class);
    }

    // اگر این واحد در سطح استان باشد
    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    // اگر این واحد مربوط به شهرستان باشد
    public function county()
    {
        return $this->belongsTo(County::class);
    }

    // رابطه برای ساختار سلسله مراتب: والد
    public function parent()
    {
        return $this->belongsTo(OrganizationalUnit::class, 'parent_id');
    }

    // رابطه برای ساختار سلسله مراتب: فرزندان
    public function children()
    {
        return $this->hasMany(OrganizationalUnit::class, 'parent_id');
    }
}