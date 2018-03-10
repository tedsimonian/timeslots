<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Calendar extends Model
{
    protected $fillable = [
        'user_id','increment','event_duration','price'
    ];

    public function user(){

        return $this->belongsTo('App\User');
    }
}
