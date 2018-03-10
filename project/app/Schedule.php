<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'user_id','calendar_id','monday_available','tuesday_available','wednesday_available','thursday_available','friday_available','saturday_available','sunday_available'
    ];


    public function timeslots()
    {
        return $this->hasMany(Timeslot::class);
    }

}
