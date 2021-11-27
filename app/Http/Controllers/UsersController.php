<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;//引入user模型
use Auth;//引入Auth
use Mail;//引入Mail接口
class UsersController extends Controller
{

     //过滤未登录用户的 edit
    public function __construct()
    {
        //except 方法指除了后面指定动作（方法名）外，其他动作都需要登录才可以执行
        $this->middleware('auth',[
            'except'=>['show','create','store','index','confirmEmail']
        ]);
        // only方法只让未登录用户访问注册页面：
        $this->middleware('guest', [
        'only' => ['create']
        ]);

        // 限流 一个小时内只能提交 10 次请求；
        $this->middleware('throttle:10,60', [
            'only' => ['store']
        ]);

    }
    //用户列表
	public function index()
	{
		// $users = User::all();查询所有
        $users = User::paginate(10);//显示6条
		return view('users.index',compact('users'));
	}

    public function create()
    {
        return view('users.create');
    }

    public function show(user $user)
    {
        //微博动态的读取逻辑
        $statuses = $user->statuses()
                         ->orderBy('created_at', 'desc')
                         ->paginate(10);
        return view('users.show',compact('user','statuses'));
    }

    public function store(Request $request)
    {
        //数据验证
        $this->validate($request,[
            'name' => 'required|unique:users|max:50',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|confirmed|min:6'
        ]);
        //创建数据 create
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

		// Auth::login($user);
  //       session()->flash('success','欢迎，您~~~~');
  //       return redirect()->route('users.show',[$user]);
        $this->sendEmailConfirmationTo($user);//激活邮箱的发送操作
        session()->flash('success','验证邮件以发送到你的注册邮箱上，请注意查收。');
        return redirect('/');
    }
    //编辑用户
    public function edit(User $user)
    {
        $this->authorize('update',$user);
        return view('users.edit',compact('user'));
    }

    //验证是否正确修改资料内容
    public function update(User $user,Request $request)
    {
        $this->authorize('update',$user);
        $this->validate($request,[
            'name' => 'required|max:50',
            'password' => 'nullable|confirmed|min:6'
        ]);

        $data = [];
        $data['name'] = $request->name;
        if($request->password){
            $data['password']=bcrypt($request->password);
        }
        $user->update($data);

        session()->flash('success','个人资料修改成功！');

        return redirect()->route('users.show', $user);
    }

    //删除用户
    public function destroy(User $user)
    {
        $this->authorize('destroy',$user);
        $user->delete();
        session()->flash('success','成功删除用户！');
        return back();
    }

    //激活邮箱发送
    protected function sendEmailConfirmationTo($user)
    {
        $view = 'emails.confirm';
        $data = compact('user');
        $to = $user->email;
        $subject = "感谢注册 Weibo 应用！请确认你的邮箱。";
        Mail::send($view, $data, function ($message) use ($to,$subject) {
           $message->to($to)->subject($subject);
        });
    }

    //激活成功
    public function confirmEmail($token)
    {
        $user = User::where('activation_token', $token)->firstOrFail();
        $user->activated = true;
        $user->activation_token = null;
        $user->save();

        Auth::login($user);
        session()->flash('success', '恭喜你，激活成功！');
        return redirect()->route('users.show', [$user]);
    }
}
