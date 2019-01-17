<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Answer extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'content','valid','question_id','file_id'
    ];

    public function question()
    {
    	return $this->belongsTo('App\Question');
    }

    public function file()
    {
        return $this->hasOne('App\File');
    }
}
