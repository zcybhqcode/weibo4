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
