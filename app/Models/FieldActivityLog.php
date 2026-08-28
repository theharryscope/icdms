<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FieldActivityLog extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function volunteer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function community()
    {
        return $this->belongsTo(Community::class);
    }
}