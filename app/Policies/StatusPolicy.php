<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

// 引入用户模型和微博模型
use App\Models\Status;


//微博授权策略
class StatusPolicy
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
    
    public function destroy(User $user, Status $status)
    {
       return $user->id === $status->user_id;
    }
    
}
