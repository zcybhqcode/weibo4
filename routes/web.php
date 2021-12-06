<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

//普通路由
Route::get('/', 'StaticPagesController@home')->name('home');//主页
Route::get('/help', 'StaticPagesController@help')->name('help');//帮助页面
Route::get('/about', 'StaticPagesController@about')->name('about');//关于页面

//明名路由
Route::get('signup', 'usersController@create')->name('signup');//注册页面

//资源路由 包括了7个路径
Route::resource('users', 'UsersController');//用户资源，等同于
// {
// Route::get('/users', 'UsersController@index')->name('users.index');==>显示所有用户列表的页面
// Route::get('/users/create', 'UsersController@create')->name('users.create');==>显示用户个人信息的页面
// Route::get('/users/{user}', 'UsersController@show')->name('users.show');==>创建用户的页面
// Route::post('/users', 'UsersController@store')->name('users.store');==>创建用户
// Route::get('/users/{user}/edit', 'UsersController@edit')->name('users.edit');==>编辑用户个人资料的页面
// Route::patch('/users/{user}', 'UsersController@update')->name('users.update');==>更新用户
// Route::delete('/users/{user}', 'UsersController@destroy')->name('users.destroy');==>删除用户
// }

//会话路由
Route::get('login','SessionsController@create')->name('login');//显示登录页面
Route::post('login', 'SessionsController@store')->name('login');//创建新会话（登录）
Route::delete('logout','SessionsController@destroy')->name('logout');//销毁会话（退出登录）

//创建用户编辑路由
// Route::get('/users/{user}/edit','UsersController@edit')->name('users.edit');

//创建激活链接路由
Route::get('signup/confirm/{token}','UsersController@confirmEmail')->name('confirm_email');

//创建密码重设的路由
//showLinkRequestForm —— 填写 Email 的表单
Route::get('password/reset', 'PasswordController@showLinkRequestForm')->name('password.request');//sendResetLinkEmail —— 处理表单提交，成功的话就发送邮件，附带 Token 的链接
Route::post('password/email', 'PasswordController@sendResetLinkEmail')->name('password.email');//showResetForm —— 显示更新密码的表单，包含 token
Route::get('password/reset/{token}', 'PasswordController@showResetForm')->name('password.reset');//reset —— 对提交过来的 token 和 email 数据进行配对，正确的话更新密码
Route::post('password/reset', 'PasswordController@reset')->name('password.update');

//微博路由
Route::resource('statuses','StatusesController', ['only' => ['store','destroy']]);

//用户关注者列表和粉丝列表的路由
Route::get('/users/{user}/followings', 'UsersController@followings')->name('users.followings');//粉丝
Route::get('/users/{user}/followers', 'UsersController@followers')->name('users.followers');//关注

//用户关注
Route::post('/users/followers/{user}', 'FollowersController@store')->name('followers.store');
//取消关注
Route::delete('/users/followers/{user}', 'FollowersController@destroy')->name('followers.destroy');