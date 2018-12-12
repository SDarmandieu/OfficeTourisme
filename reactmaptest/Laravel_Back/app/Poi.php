<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Poi extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'lat', 'lon', 'desc',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
}
