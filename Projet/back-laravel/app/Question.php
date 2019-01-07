<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Question extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'content','image','expe','point_id'
    ];

    public function point()
    {
    	return $this->belongsTo('App\Point');
    }

    public function images()
    {
    	return $this->belongsToMany('App\Images');
    }

    public function answers()
    {
    	return $this->hasMany('App\Answer');
    }
}
