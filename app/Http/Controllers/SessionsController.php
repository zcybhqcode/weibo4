<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;//引入Auth
//会话控制器
class SessionsController extends Controller
{
    public function __construct()
    {
        // 只让未登录用户访问注册页面：
        $this->middleware('guest', [
        'only' => ['create']
        ]);
    }

    //加入create 动作，并返回一个指定的登录视图
    public function create()
	{
        return view('sessions.create');
	}
    //数据验证
    public function store(Request $request)
    {
        $credentials = $this->validate($request,[
            'email' => 'required|email|max:255',
            'password' => 'required'
        ]);
       //Auth::user()方法==>获取当前登录用户的信息
       if (Auth::attempt($credentials,$request->has('remember'))){
           if(Auth::user()->activated){
               session()->flash('success','欢迎回来');
               $fallback = route('users.show',Auth::user());
               return redirect()->intended($fallback);
           }else{
               Auth::logout();
               session()->flash('warning','你的账号未激活，请检查邮箱中的注册邮件进行激活。');
               return redirect('/');
           }
       }else{
           session()->flash('danger','抱歉,请输入正确的邮箱或密码');
           return redirect()->back()->withInput();
       }

    }
	//用户退出
	public function destroy()
	{
		Auth::logout();
		session()->flash('success','您已成功退出');
		return redirect('login');
	}

}
