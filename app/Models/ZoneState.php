<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZoneState extends Model
{
    use HasFactory;

    protected $fillable = ['zone_id', 'name', 'state_coordinator_id'];

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    public function coordinator()
    {
        return $this->belongsTo(User::class, 'state_coordinator_id');
    }

    public function localGovernments()
    {
        return $this->hasMany(LocalGovernment::class);
    }
}