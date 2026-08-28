<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $guarded = [];

    // Geographic Relationships
    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    public function zoneState()
    {
        return $this->belongsTo(ZoneState::class, 'zone_state_id');
    }

    public function localGovernment()
    {
        return $this->belongsTo(LocalGovernment::class, 'local_government_id');
    }
}