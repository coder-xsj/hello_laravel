<?php

namespace App\Http\Controllers;
use Auth;
use Cache;
use Illuminate\Http\Request;
use Gregwar\Captcha\CaptchaBuilder;
use Gregwar\Captcha\PhraseBuilder;
class SessionsController extends Controller
{
    //
    public function __construct(){
        // 让未登录用户只能访问登录和注册页面
        $this->middleware('guest', [
            'only' => ['create', 'signup', 'img'],
        ]);
    }

    public function create(){
        return view('sessions.create');
    }
    // 登录验证
    public function store(Request $request){
            $auth = $this->validate($request,[
                'email' => 'required|email|max:255',
                'password' => 'required|',
                'captcha' => 'required',
            ]);
            unset($auth['captcha']); //attempt会查询这个字段数据库,所有释放掉
             // 从缓存中取出
            $captcha_cache_content = Cache::get('captcha_content');
            if(strtolower($captcha_cache_content) !== strtolower($request->captcha)){
                session()->flash('danger', '验证码错误');
                return redirect()->back()->withInput();
            }

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
    // 产生验证码
    public function getCaptcha(){
        $phraseBuilder = new PhraseBuilder(4);
        $builder = new CaptchaBuilder(null, $phraseBuilder); //参数传给cap构造类
        $captcha = $builder->build();   //生成图片验证码
        $captcha_content = $captcha->getPhrase();   // 获取图片验证码中的内容
        // 将数据存储到缓存中，时间为2分钟
        Cache::put('captcha_content', $captcha_content, 120);
        // 从缓存中取出
//        $captcha_cache_content = Cache::get('captcha_content');
//        dd($captcha_cache_content);
        $captcha->save('out.jpg');
        $captcha_base64_content = $captcha->inline();  // 转化成base64
//    return [$captcha_content, $captcha_cache_content];
//    info($captcha_base64_content);  //写入log
        return [
            'img' => $captcha_base64_content,
        ];
    }
    public function logout(){
        Auth::logout();
        session()->flash('success', '您已成功退出');
        return redirect('login');
    }

}
