<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Sort;

class Forum extends Model
{
	protected $guarded=[];
	public $primaryKey = 'id';
    public function users()
    {
    	return $this->belongsTo('App\User');
    }
    public function posts()
    {
        return $this->hasMany('App\Models\Post');
    }
    public function sorts()
    {
        return $this->belongsTo('App\Models\Sort','sort_id','id');
    }
}
