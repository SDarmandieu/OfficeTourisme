<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class City extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','lon','lat'
    ];

    public function games()
    {
    	return $this->hasMany('App\Game');
    }

    public function points()
    {
        return $this->hasMany('App\Point');
    }

    public function files()
    {
        return $this->hasMany('App\File');
    }
}
