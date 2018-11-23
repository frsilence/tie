<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Forum;
class Sort extends Model
{
    protected $guarded=[];
    public function forums()
    {
    	return $this->hasMany('App\Models\Forum');
    }
}
