<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomEvent extends Model
{
    protected $fillable = [
       'employee_id','day','start','end','title','description','color','color_id'
    ];

    public function employee(){

        return $this->belongsTo('App\User','employee_id');
    }
}
