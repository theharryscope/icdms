<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Project extends Model
{
    use HasFactory, LogsActivity;

    protected $guarded = [];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'budget' => 'decimal:2',
        'expenditure' => 'decimal:2',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['title', 'status', 'budget', 'expenditure'])
            ->logOnlyDirty()
            ->useLogName('project_management');
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function community()
    {
        return $this->belongsTo(Community::class);
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'project_manager_id');
    }

    public function kpis()
    {
        return $this->hasMany(Kpi::class);
    }

    public function monitoringVisits()
    {
        return $this->hasMany(FieldMonitoringVisit::class);
    }
}