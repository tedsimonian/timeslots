<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [
        'user_id','notifications','gcalendar','address','city','state','postal_code','access_token'
    ];

    public function user(){

        return $this->belongsTo('App\User');
    }
}
