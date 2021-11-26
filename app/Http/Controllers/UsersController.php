<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;//引入user模型
use Auth;//引入Auth

class UsersController extends Controller
{

     //过滤未登录用户的 edit
    public function __construct()
    {
        //except 方法指除了后面指定动作（方法名）外，其他动作都需要登录才可以执行
        $this->middleware('auth',[
            'except'=>['show','create','store','index']
        ]);
        // only方法只让未登录用户访问注册页面：
        $this->middleware('guest', [
        'only' => ['create']
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
        return view('users.show',compact('user'));
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

		Auth::login($user);
        session()->flash('success','欢迎，您~~~~');
        return redirect()->route('users.show',[$user]);
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


}
