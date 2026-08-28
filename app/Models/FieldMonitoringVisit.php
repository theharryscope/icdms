<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FieldMonitoringVisit extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'field_officer_id',
        'visit_date',
        'latitude',
        'longitude',
        'observations',
        'challenges',
        'recommendations',
        'status',
    ];

    protected $casts = [
        'visit_date' => 'date',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function officer()
    {
        return $this->belongsTo(User::class, 'field_officer_id');
    }
}