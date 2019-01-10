<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Image extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'filename','path','alt','imagetype_id','city_id'
    ];

    public function questions()
    {
    	return $this->belongsToMany('App\Question');
    }

    public function games()
    {
    	return $this->hasMany('App\Game');
    }

    public function answers()
    {
    	return $this->belongsToMany('App\Answer');
    }

    public function imagetype()
    {
    	return $this->belongsTo('App\Imagetype');
    }

    public function city()
    {
        return $this->belongsTo('App\City');
    }


}
