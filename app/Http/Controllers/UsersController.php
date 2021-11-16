<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;//引入user模型
use Auth;//引入Auth

class UsersController extends Controller
{
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

}
