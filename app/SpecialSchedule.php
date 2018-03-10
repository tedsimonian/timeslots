<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SpecialSchedule extends Model
{
    protected $fillable = [
        'user_id','calendar_id','day','available'
    ];

    public function specialTimeslots()
    {
        return $this->hasMany(SpecialTimeslot::class);
    }
}
