<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Person extends Model
{
    //
    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }
    public function estekhdam(): BelongsTo
    {
        return $this->belongsTo(Estekhdam::class, 'e_id');
    }
    public function radif(): BelongsTo
    {
        return $this->belongsTo(Radif::class, 'r_id');
    }
    public function semat(): BelongsTo
    {
        return $this->belongsTo(Semat::class, 's_id');
    }
    public function tahsil(): BelongsTo
    {
        return $this->belongsTo(Tahsil::class, 't_id');
    }
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'u_id');
    }
}
