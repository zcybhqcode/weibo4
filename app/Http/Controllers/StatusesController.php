<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Status;
use Auth;

class StatusesController extends Controller
{
    //store 和 destroy 动作将用于对微博的创建和删除
    //借助 Auth 中间件来为这两个动作添加过滤请求
	public function __construct()
	{
        $this->middleware('auth');
	}

    public function store(Request $request)
    {
        $this->validate($request, [
            'content' => 'required|max:140'
        ]);
        Auth::user()->statuses()->create([
            'content' => $request['content']
        ]);
        session()->flash('success', '发布成功！');
        return redirect()->back();
    }
}
