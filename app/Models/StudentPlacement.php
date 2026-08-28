<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentPlacement extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function employer()
    {
        return $this->belongsTo(Employer::class);
    }
}