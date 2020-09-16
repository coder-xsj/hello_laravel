<?php

namespace App\Models;

use http\Env\Request;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Auth;
class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    protected $table = 'users';

    public function gravatar($size = '100'){
        $hash = md5(strtolower(trim($this->attributes['email'])));
        return "http://www.gravatar.com/avatar/$hash?s=$size"; //生成随机头像
    }

    public static function boot(){
        parent::boot();
        // 被创建前生成令牌
        // creating 用于监听模型被创建之前的事件
        static::creating(function ($user){
            $user->activation_token = Str::random(10);
        });
    }

    public function statuses(){
        // 可以理解为 一个User有多个Status（文章）
        return $this->hasMany(Status::class);
    }
    // 取出当前用户所发布的微博
    public function feed(){
        $user_ids = $this->followings->pluck('id')->toArray();
        array_push($user_ids, $this->id);
        return Status::whereIn('user_id', $user_ids)
            ->with('user')
            ->orderBy('created_at', 'desc');
//        return $this->statuses()
//                    ->orderBy('created_at', 'desc');
    }
    // 一个用户能够拥有多个粉丝
    public function followers(){
        return $this->belongsToMany(User::class, 'followers', 'user_id', 'follower_id');
    }
    // 一个粉丝能够关注多个人
    public function followings(){
        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'user_id');
    }
    // 关注
    public function follow($user_ids){
        if(! is_array($user_ids)){
            $user_ids = compact('user_ids');
        }
        $this->followings()->sync($user_ids, false);
    }
    // 取消关注
    public function unfollow($user_ids){
        if(! is_array($user_ids)){
            $user_ids = compact('user_ids');
        }
        $this->followings()->detach($user_ids);
    }
    public function isFollowing($user_id){
        return $this->followings->contains($user_id);
    }


}
