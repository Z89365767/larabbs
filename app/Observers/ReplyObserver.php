<?php

namespace App\Observers;

use App\Models\Reply;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class ReplyObserver
{
    public function creating(Reply $reply)
    {
        //我们将使用 HTMLPurifier 来修复此问题。与话题的类似地，我们将在模型监控器的 creating 事件中对 content 字段进行净化处理：
        $reply->topic->increment('reply_count',1);

        //话题回复的内容限定与话题的内容无异，因此我们使用同样的过滤规则 —— user_topic_body
        $reply->content = clean($reply->content,'user_topic_body');
    }

    public function updating(Reply $reply)
    {
        //
    }
}