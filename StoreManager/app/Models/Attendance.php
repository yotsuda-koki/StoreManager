<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function break_times()
    {
        return $this->hasMany(\App\Models\BreakTime::class);
    }

    public function working_time()
    {
        return $this->hasOne(\App\Models\WorkingTime::class);
    }
}
