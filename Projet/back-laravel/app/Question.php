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
        'content','expe','point_id','game_id','image_id'
    ];

    public function point()
    {
    	return $this->belongsTo('App\Point');
    }

    public function image()
    {
    	return $this->belongsTo('App\Image');
    }

    public function answers()
    {
    	return $this->hasMany('App\Answer');
    }

    public function game()
    {
        return $this->belongsTo('App\Game');
    }
}
