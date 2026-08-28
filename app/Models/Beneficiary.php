<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Beneficiary extends Model
{
    use HasFactory;

    protected $fillable = [
        'beneficiary_code',
        'full_name',
        'gender',
        'age',
        'phone',
        'community_id',
        'category',
    ];

    public function community()
    {
        return $this->belongsTo(Community::class);
    }
}