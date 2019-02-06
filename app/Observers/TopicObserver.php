<?php

namespace App\Observers;

use App\Models\Topic;
use App\Handlers\SlugTranslateHandler;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class TopicObserver
{
//    public function creating(Topic $topic)
//    {
//        //
//    }
//
//    public function updating(Topic $topic)
//    {
//        //
//    }

    //make_excerpt() 是我们自定义的辅助方法，我们需要在 bootstrap文件夹的helpers.php 文件中添加：
    public function saving(Topic $topic)
    {
        //XSS  过滤
        $topic->body = clean($topic->body,'user_topic_body');

        //生成话题摘录
        $topic->excerpt = make_excerpt($topic->body);

        //如slug字段无内容,即使用翻译器对title进行翻译
        if(!$topic->slug)
        {
            //app() 允许我们使用 Laravel 服务容器 ，此处我们用来生成 SlugTranslateHandler 实例。
            $topic->slug = app(SlugTranslateHandler::class)->translate($topic->title);
        }
    }
}