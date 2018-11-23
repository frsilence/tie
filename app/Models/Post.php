<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $guarded=[];
    public function users()
    {
    	return $this->belongsTo('App\User','user_id','id');
    }
    public function forums()
    {
    	return $this->belongsTo('App\Models\Forum','forum_id','id');
    }
    public function comments()
    {
        return $this->hasMany('App\Models\Comment');
    }
}
