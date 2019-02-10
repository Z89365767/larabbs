<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Reply;


//Laravel 会检测 ShouldQueue 接口并自动将通知的发送放入队列中，所以我们不需要做其他修改。
class TopicReplied extends Notification implements ShouldQueue
{
    use Queueable;

    public $reply;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Reply $reply)
    {
        //注入回复实体,方便toDatabase方法中的使用
        $this->reply = $reply;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        //每个通知类都有个 via() 方法，它决定了通知在哪个频道上发送。我们写上 database 数据库来作为通知频道。
        return ['database','mail'];
    }

    public function toDatabase($notifiable)
    {
        $topic  = $this->reply->topic;

        $link = $topic->link(['#reply' . $this->reply->id]);

        //存入数据库里的数据
        return [
            'reply_id' => $this->reply->id,

            'reply_content' => $this->reply->content,

            'uses_id' => $this->reply->user->id,

            'user_name' => $this->reply->user->name,

            'user_avatar' => $this->reply->user->avatar,

            'topic_link' =>$link,

            'topic_id' => $topic->id,

            'topic_title' => $topic->title,

        ];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = $this->reply->topic->link(['#reply',$this->reply->id]);

        return (new MailMessage)
                    ->line('你的话题有新的回复!')
                    ->action('查看回复', url($url));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
