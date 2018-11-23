<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Userattention extends Model
{
	protected $table = "userattentions";
	protected $guarded=[];
    public function user()
    {
    	return $this->belongsTo('App\User','user_id','id');
    }
}
