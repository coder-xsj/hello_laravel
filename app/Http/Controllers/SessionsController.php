<?php

namespace App\Http\Controllers;
use Auth;
use Illuminate\Http\Request;

class SessionsController extends Controller
{
    //
    public function __construct(){
        // 让未登录用户只能访问登录和注册页面
        $this->middleware('guest', [
            'only' => ['create', 'signup'],
        ]);
    }

    public function create(){
        return view('sessions.create');
    }
    public function store(Request $request){
            $auth = $this->validate($request,[
                'email' => 'required|email|max:255',
                'password' => 'required|'
            ]);
//            dd($auth);
            if(Auth::attempt($auth, $request->has('remember'))){
                if(Auth::user()->activated){
                    session()->flash('success', '欢迎回来');
                    $fallback = route('users.show', [Auth::user()]);
                    return redirect()->intended($fallback);
                }else{
                    Auth::logout();
                    session()->flash('warning', '你的账号未激活，请检查邮箱中的注册邮件进行激活。');
                    redirect('/');  // 重定向到Home
                }
            } else {
                session()->flash('danger', '很抱歉，您的邮箱和密码不匹配');
                return redirect()->back()->withInput();  //返回上一个路由，并且数据和{{ old('email') }} 对应
            }
    }

    public function logout(){
        Auth::logout();
        session()->flash('success', '您已成功退出');
        return redirect('login');
    }

}
