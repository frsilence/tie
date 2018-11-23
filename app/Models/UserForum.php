<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserForum extends Model
{
    public $table = 'users_forums';
    public function users()
    {
    	return $this->belongsToMany('App\User');
    }
}
