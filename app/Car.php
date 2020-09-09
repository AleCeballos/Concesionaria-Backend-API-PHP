<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    //indico la relacion entre tablas

    protected $table='cars'; //tabla

    //relacion

    public function user(){

        return $this->belongsTo('App\User','user_id');
    }

}
