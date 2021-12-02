<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($user){
            $user->activation_token = Str::random(10);
        });
    }

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function gravatar($size = '100')
    {
        $hash = md5(strtolower(trim($this->attributes['email'])));
        return "https://cravatar.cn/avatar/$hash?s=$size";
    }

    //在用户模型中，指明一个用户拥有多条微博。
    public function statuses()
    {
        return $this->hasMany(Status::class);
    }

    //定义一个 feed 方法，使用该方法来获取当前用户关注的人发布过的所有微博动态
    public function feed()
    {
        return $this->statuses()
                    ->orderBy('created_at', 'desc');
    }

	//使用 belongsToMany 来关联模型之间的多对多关系
    public function followers()//通过 followers 来获取粉丝关系列表
    {
        return $this->belongsToMany(User::class, 'followers', 'user_id','follower_id');
    }
    public function followings()//通过 followings 来获取用户关注人列表
    {
        return $this->belongsToMany(User::class, 'followers', 'follower_id','user_id');
    }

    //定义关注（ follow ）和取消关注（ unfollow ）
    //关注
    public function follow($user_ids)
    {
        if ( ! is_array($user_ids)) {//is_array 用于判断参数是否为数组
            $user_ids = compact('user_ids');
        }
        $this->followings()->sync($user_ids, false);
    }
    //取消关注
    public function unfollow($user_ids)
    {
        if ( ! is_array($user_ids)) {
            $user_ids = compact('user_ids');
        }
        $this->followings()->detach($user_ids);
    }
    //用 contains 方法判断当前登录的用户 A 是否关注了用户 B
    public function isFollowing($user_id)
    {
        return $this->followings->contains($user_id);
    }
}
