<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
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
    // 登录账户是否和当前user-id一致才可更新自己的信息
    public function update(User $currentUser, User $user){
        return $currentUser->id === $user->id;
    }
    // 是否为管理员并且不可删除自己
    public function destroy(User $currentUser, User $user){
        return $currentUser->is_admin && $currentUser->id !== $user->id;
    }
    // 自己不能关注自己
    public function follow(User $currentUser, User $user){
        return $currentUser->id !== $user->id;
    }
}
