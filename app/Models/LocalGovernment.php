<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocalGovernment extends Model
{
    use HasFactory;

    protected $fillable = ['zone_state_id', 'name', 'lga_coordinator_id', 'project_leader_id'];

    public function state()
    {
        return $this->belongsTo(ZoneState::class, 'zone_state_id');
    }

    public function lgaCoordinator()
    {
        return $this->belongsTo(User::class, 'lga_coordinator_id');
    }

    public function projectLeader()
    {
        return $this->belongsTo(User::class, 'project_leader_id');
    }
}