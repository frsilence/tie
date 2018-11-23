<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Post;
use App\User;

class PostPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    /**
     * 判定给定用户是否有权限操作文章
     */
    public function create_post(User $user,Post $post)
    {
        return $user->hasAccess('create_post') or $user->admin == "true";
    }
    public function update_post(User $user,Post $post)
    {
        return $user->hasAccess('update_post') or $user->id == $post->user_id or $user->admin == "true";
    }
    public function delete_post(User $user,Post $post)
    {
        return $user->hasAccess('delete_post') or $user->id == $post->user_id or $user->admin == "true";

    }

}
