<?php

namespace App\Http\Controllers;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Mail;

class UsersController extends Controller
{
    //
    public function __construct(){
        // 放行控制路由
        $this->middleware('auth',[
           'except' => ['show', 'create', 'store', 'index', 'confirmEmail']
        ]);
    }
    public function index(){
        $users = User::paginate(10);
        return view('users.index', compact('users'));
    }
    public function create(){
        return view('users.create');
    }
    public function show(User $user){
        return view('users.show', compact('user'));
    }
    // 用户注册
    public function store(Request $request){
        $this->validate($request, [
            'name' => 'required|unique:users|min:3|max:50',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|confirmed|min:6',
        ]);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        $this->sendEmailConfirmationTo($user);
        session()->flash('success', '验证邮箱已经发送到你的邮箱上，请注意查收。');
        return redirect('/'); //

    }
    // 测试发送邮箱方法
    protected function  sendEmailConfirmationTo($user){
            $view = 'emails.confirm';
            $data = compact('user');
            $from = '2449382518@qq.com';
            $name = 'xsj';
            $to = $user->email;
            $subject = '感谢注册 Weibo 应用！请确认你的邮箱';

            Mail::send($view, $data, function ($message) use ($from, $name, $to, $subject){
                $message->from($from, $name)->to($to)->subject($subject);
            });
    }

    public function confirmEmail($token){
        $user = User::where('activation_token', $token)->firstOrFail();
        $user->activated = true;
        $user->activation_token = null;
        $user->save();

        Auth::login($user);
        // flash只供下一次请求使用
        session()->flash('success', '恭喜你，激活成功!');
        return redirect()->route('users.show', [$user]);
    }

    public function edit(User $user){
        $this->authorize('update', $user);
        return view('users.edit', compact('user'));
    }
    public function update(User $user, Request $request){
        $this->authorize('update', $user);
        $this->validate($request,[
           'name' => 'required|max:50',
            'password' => 'nullable|min:6'
        ]);
        $data = [];
        $count = $user->where('name', $request->name)->count();
        if($count == 0){
            $data['name'] = $request->name;
        }

        if($request->password){
            $data['passowrd'] = crypt($request->password);
        }
        if($data){
            $user->update($data);
        }
        session()->flash('success', "个人资料更新成功");
        return redirect()->route('users.show', $user->id);
    }
    public function destroy(User $user){
        $this->authorize('destroy', $user);
        $user->delete();
        session()->flash('success', '用户删除成功');
        return back();
    }


}
