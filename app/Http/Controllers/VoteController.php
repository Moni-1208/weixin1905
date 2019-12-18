<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Redis;

class VoteController extends Controller
{
    //
    public function index()
    {
    	echo'<pre>';print_r($_GET);echo '</pre>';
    	$code=$_GET['code'];
    	// 获取access_token
    	$data=$this->getAccessToken($code);
    	// 获取用户信息 xxx
    	$user_info=$this->getUserInfo($data['access_token'],$data['openid']);
    	// 处理业务逻辑  
    	// TODO 判断是否已经投过  使用redis 集合 或 有序集合
    	$openid=$user_info['openid'];
    	$key='s:vote:xiaobai';
    	Redis::sadd($key,$openid);

    	$members=Redis::members($key);
    	echo'<pre>';print_r($numbers);echo '</pre>'; // 获取所有投票人的openid
    	$total=Redis::scard($key);  // 统计投票总人数
    	echo "投票总人数：".$total;
    	echo "<hr>";
    	echo '<pre>';print_r($number);echo '</pre>';
    }

    /**
     *  获取 access_token
     */
    protected function getAccessToken($code)
    {
    	$url='https://api.weixin.qq.com/sns/oauth2/access_token?appid=wxe10b6a253c208edb&secret=0b5c451ab2ee2724d44b177a49bedb4b&code='.$code.'&grant_type=authorization_code';
    	$json_data=file_get_contents($url);
    	return json_decode($json_data,true);
    }

    /**
    *  获取用户信息
    */
    protected function getUserInfo($access_token,$openid)
    {
    	$url='https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
    	$json_data=file_get_contents($url);
    	$data=json_decode($json_data,true);
    	// echo'<pre>';print_r($user_info);echo '</pre>';
    	if (isset($data['errcode'])) {
    		// TODO 错误处理
    		die("出错了 40001"); // 获取用户信息失败
    	}

    	return $data;
    }
}
