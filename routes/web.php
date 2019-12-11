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

// 点我
Route::get('test/add',"Test\TestController@add");

// hello
Route::get('test/hello',"Test\TestController@hello");

// 添加到数据库
Route::get('user/addUser',"User\LoginController@addUser");

// phpinfo
Route::get('test/phpinfo',"Test\TestController@phpinfo");

// 访问百度
Route::get('test/baidu',"Test\TestController@baidu");

// 测试字符串转数组
Route::get('test/xmlTest',"Test\TestController@xmlTest");

// 微信接口测试
Route::get('/wx',"Weixin\WxController@wechat");

// 接受微信推送事件
Route::post('/wx',"Weixin\WxController@receiv");
//微信测试
Route::get('/textinfo',"Weixin\WxController@textinfo");

// 获取用户基本信息（openID）
Route::get('wx/getUserInfo',"Weixin\WxController@getUserInfo");
