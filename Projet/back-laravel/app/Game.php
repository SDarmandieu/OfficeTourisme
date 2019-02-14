<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Game extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','age','desc','city_id','published'
    ];

    public function city()
    {
    	return $this->belongsTo('App\City');
    }

    public function points()
    {
    	return $this->belongsToMany('App\Point');
    }

    public function questions()
    {
        return $this->hasMany('App\Question');
    }

    public function files()
    {
        return $this->belongsToMany('App\File');
    }
}
