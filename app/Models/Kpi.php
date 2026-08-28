<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kpi extends Model
{
    use HasFactory;

    protected $table = 'kpis';

    protected $fillable = [
        'project_id',
        'title',
        'unit',
        'baseline',
        'target',
        'current',
        'frequency',
        'assigned_officer_id',
    ];

    protected $casts = [
        'baseline' => 'decimal:2',
        'target' => 'decimal:2',
        'current' => 'decimal:2',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function assignedOfficer()
    {
        return $this->belongsTo(User::class, 'assigned_officer_id');
    }
}