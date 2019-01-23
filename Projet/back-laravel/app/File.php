<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class File extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'filename','path','type','extension','alt','imagetype_id','city_id'
    ];

    public function questions()
    {
        return $this->hasMany('App\Question');
    }

    public function games()
    {
        return $this->belongsToMany('App\Game');
    }

    public function answers()
    {
        return $this->hasMany('App\Answer');
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
