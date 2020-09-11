<?php

namespace App\Http\Controllers;
use Auth;
use Illuminate\Http\Request;

class SessionsController extends Controller
{
    //
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
                session()->flash('success', '欢迎回来');
                return redirect()->route('users.show', [Auth::user()]);
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
