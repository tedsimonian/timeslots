<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GoogleCalendar extends Model
{
    protected $fillable = [
        'user_id','title','calendar_id'
    ];
}
