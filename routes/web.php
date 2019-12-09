<?php

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

Route::get('/', function () {
    return view('welcome');
});

// hello
Route::get('test/hello',"Test\TestController@hello");

// 添加到数据库
Route::get('user/addUser',"User\LoginController@addUser");

// phpinfo
Route::get('test/phpinfo',"Test\TestController@phpinfo");

// 访问百度
Route::get('test/baidu',"Test\TestController@baidu");

// 微信接口测试
Route::get('wx',"wx\TestController@wechat");

// 接受微信推送事件
Route::get('wx/receiv',"wx\TestController@receiv");

// 获取用户基本信息（openID）
Route::post('wx/openID',"wx\TestController@openID");

