<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Point extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'lat','lon','desc'
    ];

    public function games()
    {
    	return $this->belongsToMany('App\Game');
    }

    public function questions()
    {
    	return $this->hasMany('App\Question');
    }
}
