<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;

class FollowersController extends Controller
{
    //自己不能关注自己。故新增授权策略方法取名 follow()
    // public function follow(User $currentUser, User $user)
    // {
    //     return $currentUser->id !== $user->id;
    // }
	public function __construct()
	{
		$this->middleware('auth');
	}
	public function store(User $user)
	{
		$this->authorize('follow', $user);
		if ( ! Auth::user()->isFollowing($user->id)) {
			Auth::user()->follow($user->id);
		}
		return redirect()->route('users.show', $user->id);
	}
	public function destroy(User $user)
	{
		$this->authorize('follow', $user);
		if (Auth::user()->isFollowing($user->id)) {
			Auth::user()->unfollow($user->id);
		}
		return redirect()->route('users.show', $user->id);
	}
}
