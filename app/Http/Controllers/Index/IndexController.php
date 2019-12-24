<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Model\WxUserModel;
use Illuminate\Support\Str;

class IndexController extends Controller
{
    public function index()
    {
    	$code=$_GET['code'];
    	$data=$this->getAccessToken($code);
    	// 判断用户是否已存在
    	$openid=$data['openid'];
    	$u=WxUserModel::where(['openid'=>$openid])->first();
    	if ($u) {
    		$user_info=$u->toArray();
    	}else{   		
    	// 获取用户信息
    	$user_info=$this->getUserInfo($data['access_token'],$data['openid']);
    		// 入库
    		WxUserModel::insertGetId($user_info);
    	}

        // 微信配置
        $nonceStr=Str::random(8);
        $signature=[
            'appId'=>'wxe10b6a253c208edb',
            'timestamp'=>time(),
            'nonceStr'=>$nonceStr
        ];
        $ticket=WxUserModel::getJsapiTicket(); // 获取jsapi_ticket
        $url=$_SERVER['APP_URL'].$_SERVER['REQUEST_URL'];; //当前URL
        $jsapi_signature = WxUserModel::jsapiSign($ticket,$url,$wx_config);
        $wx_config['signature']=$jsapi_signature;
    	// 取头像
    	$data=[
    		'u'=>$user_info,
            'wx_config'=>$wx_config
    	];
    	return view('index.index',$data);
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
