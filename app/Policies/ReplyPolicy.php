<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Reply;

class ReplyPolicy extends Policy
{
    public function update(User $user, Reply $reply)
    {
        // return $reply->user_id == $user->id;

        return true;
    }

    public function destroy(User $user, Reply $reply)
    {
        //当前用户ID和回复帖子或者发帖ID相等就可以删除帖子
        return $user->isAuthorOf($reply) || $user->isAuthorOf($reply->topic);
    }
}
