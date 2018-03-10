<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Timeslot extends Model
{
    protected $fillable = [
        'schedule_id','day','timeslot'
    ];

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }
}
