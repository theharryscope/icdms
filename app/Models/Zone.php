<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'description', 'zonal_coordinator_id'];

    public function coordinator()
    {
        return $this->belongsTo(User::class, 'zonal_coordinator_id');
    }

    public function states()
    {
        return $this->hasMany(ZoneState::class);
    }
}