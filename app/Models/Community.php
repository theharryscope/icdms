<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Community extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'state',
        'lga',
        'latitude',
        'longitude',
        'estimated_population',
        'needs_assessment',
    ];

    protected $casts = [
        'needs_assessment' => 'array',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function beneficiaries()
    {
        return $this->hasMany(Beneficiary::class);
    }
}