<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
	protected $guarded=[];
    public function users()
    {
    	return $this->belongsTo('App\User');
    }
    public function posts()
    {
    	return $this->belongsTo('App\Models\Post');
    }
}
