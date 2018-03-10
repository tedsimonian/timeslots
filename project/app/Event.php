<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'user_id','employee_id','day','timeslot','duration','price','status','completed_at'
    ];

    public function user(){

        return $this->belongsTo('App\User');
    }

    public function employee(){

        return $this->belongsTo('App\User','employee_id');
    }
}
