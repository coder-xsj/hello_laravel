<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $fillable = ['content'];
    //
    public function user(){
        // 可以理解为 Status（文章） 属于 User
        return $this->belongsTo(User::class);   //指明一对多关系
    }
}
