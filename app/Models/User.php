<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Auth;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Traits;

class User extends Authenticatable
{
    use HasRoles;
    use Traits\ActiveUserHelper;

//默认的 User 模型中使用了 trait —— Notifiable，它包含着一个可以用来发通知的方法 notify() ，此方法接收一个通知实例做参数。虽然 notify() 已经很方便，但是我们还需要对其进行定制，我们希望每一次在调用 $user->notify() 时，自动将 users 表里的 notification_count +1 ，这样我们就能跟踪用户未读通知了。
    use Notifiable{

        //避开Notifiable中同名‘notify’方法,重写notify方法名
        notify as protected laravelNotify;
    }

    public function notify($instance)
    {
        //如果要通知的人是当前用户,就不必通知了
        if($this->id == Auth::id())
        {
            return;
        }
        ////我们对 notify() 方法做了一个巧妙的重写，现在每当你调用 $user->notify() 时， users 表里的 notification_count 将自动 +1。
        $this->increment('notification_count');

        $this->laravelNotify($instance);
    }

    //当用户访问通知列表时，将所有通知状态设定为已读，并清空未读消息数。
    public function markAsRead()
    {
        $this->notification_count = 0;

        $this->save();

        $this->unreadNotifications->markAsRead();
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','introduction','avatar',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    //关联话题模型
    public function topics()
    {
        return $this->hasMany(Topic::class);
    }

    public function isAuthorOf($model)
    {
        return $this->id == $model->user_id;
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    //属性修改器
    public function setPasswordAttribute($value)
    {

        if(strlen($value) != 60)
        {
            $value = bcrypt($value);
        };

        $this->attributes['password'] = $value;
    }

    public function setAvatarAttribute($path)
    {
        //如果不是"http"子串开头,那就是从后台上传的,需要补全URL
        if(!starts_with($path,'http'))
        {
            //拼接完整的URL
            $path = config('app.url') . "/uploads/images/avatars/$path";
        }

        $this->attributes['avatar'] = $path;
    }
}
