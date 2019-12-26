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

Route::get('/cc', function () {
    return view('welcome');
});

// 微商城

// 前台展示
Route::get('/',"Index\IndexController@index");

// 后台详情
Route::get('/goods/detail',"Goods\IndexController@detail");

// curriculum  选择课程
Route::get('/curriculum',"Index\IndexController@curriculum");



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

// 创建菜单
Route::get('/wx/menu',"Weixin\WxController@createMenu");

//微信测试
Route::get('/textinfo',"Weixin\WxController@textinfo");

// 获取用户基本信息（openID）
Route::get('wx/getUserInfo',"Weixin\WxController@getUserInfo");

// 获取用户基本信息（openID）
Route::get('wx/getMedia',"Weixin\WxController@getMedia");

// 测试access_token
Route::get('wx/getAccessToken',"Weixin\WxController@getAccessToken");

// 获取access_token
Route::get('wx/flushAccessToken',"Weixin\WxController@flushAccessToken");

// 微信公众号
Route::get('/vote',"VoteController@index");  // 微信投票hashTest

// hash 添加值测试
Route::get('/vote/hashTest',"VoteController@hashTest");

// 生成二维码
Route::get('Wx/qrcode',"Weixin\WxQRController@qrcode");

