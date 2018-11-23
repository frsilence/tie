<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password','sex','birthday','telephone','area','active','admin','user_image'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];
    public function posts()
    {
        return $this->hasMany('App\Models\Post');
    }
    public function forums()
    {
        return $this->hasMany('App\Models\Forum');
    }
    public function userattentions()
    {
        return $this->hasMany('App\Models\Userattention');
    }
    public function comments()
    {
        return $this->hasMany('App\Models\Comment');
    }
    public function users_forums()
    {
        return $this->belongsToMany('App\Models\UserForum')->withTimestamps();
    }
    public function roles()
    {
        return $this->belongsToMany('App\Models\Role','user_role','user_id','role_id')->withTimestamps();
    }
    public function hasAccess($permission)
    {
        foreach ($this->roles as $role) {
            if($role->hasAccess($permission)){
                return true;
            }
        }
        return false;
    }
    public function inRole($roleSlug)
    {
        return $this->roles()->where('slug',$roleSlug)->count()==1;
    }
}
