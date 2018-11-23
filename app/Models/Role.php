<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Permission;

class Role extends Model
{
    protected $guarded=[];
    //protected $casts = ['permissions'=>'array'];

    public function users()
    {
    	return $this->belongsToMany('App\User','user_role','role_id','user_id')->withTimestamps();
    }
    public function permissions()
    {
        return $this->belongsToMany('App\Models\Permission','roles_permissions','role_id','permission_id')->withTimestamps();
    }
    public function hasAccess($permission)
    {
    	return $this->hasPermission($permission);
    }
    public function hasPermission($permission)
    {
    	$haspermiss = Permission::where('name',$permission)->first();
        if($haspermiss){
            $mypermission = $this->permissions;
            foreach ($mypermission as $key => $value) {
                if($value->name == $permission){
                    return true;
                };
            };
            return false;
        }
        else{
            return false;
        }
    }

}
