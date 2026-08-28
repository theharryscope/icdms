<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'report_type', 'subject', 'details', 'status', 'admin_notes', 'reviewed_at',
    ];

    protected $casts = ['reviewed_at' => 'datetime'];

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}