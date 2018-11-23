<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Post;
use App\Policies\PostPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
        Post::class => PostPolicy::class,
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);

        //SuperAdmin设置权限
        $gate->define('set_adminsetting',function($user){
            return $user->admin == "true";
        });

        //分区分类设置权限
        $gate->define('set_adminsort',function($user){
            return $user->hasAccess('set_adminsort') or $user->admin == "true";
        });

        //用户权限设置权限
        $gate->define('set_adminuser',function($user){
            return $user->hasAccess('set_adminuser') or $user->admin == "true";
        });
    }
}
