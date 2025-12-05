<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{


    //enduzir o tipo do 'items' seja array e não string
    protected $casts = [
        'items' => 'array'
    ];

    protected $dates = ['date'];

    public function user() {
        return $this->belongsTo('App\Models\User');
    }
}
