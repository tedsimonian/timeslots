<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SpecialTimeslot extends Model
{
    protected $fillable = [
        'special_schedule_id','timeslot'
    ];

    public function specialSchedule()
    {
        return $this->belongsTo(SpecialSchedule::class);
    }
}
