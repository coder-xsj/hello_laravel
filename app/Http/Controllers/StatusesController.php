<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Status;
use Auth;
class StatusesController extends Controller
{
    public function __construct(){
        // 因为此块需要用户登录才可操作，故用auth添加路由过滤请求
        $this->middleware('auth');
    }
    // 发布微博
    public function store(Request $request){
        $this->validate($request, [
            'content' => 'required|max:140',
        ]);
        // 创建的微博会自动与用户进行关联
        Auth::User()->statuses()->create([
            'content' => $request['content'],
        ]);
        session()->flash('success', '发布成功！');
        return redirect()->back(); // 返回到发布页
    }

    // 删除微博
    public function destroy(){

    }
}
