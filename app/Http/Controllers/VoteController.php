<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Redis;

class VoteController extends Controller
{
    //
    public function index()
    {
    	$code=$_GET['code'];
    	// 获取access_token
    	$data=$this->getAccessToken($code);
    	// 获取用户信息
    	$user_info=$this->getUserInfo($data['access_token'],$data['openid']);
        // hash保存用户信息
        $userinfo_key='h:u:'.$data['openid'];
        // hash 存入值
        Redis::hMset($userinfo_key,$user_info);

    	// 处理业务逻辑  
    	// TODO 判断是否已经投过  使用redis 集合 或 有序集合
    	$openid=$user_info['openid'];
    	$key='ss:vote:xiaobai';

    	// 判断是否已经投过票
    	if(Redis::zrank($key,$user_info['openid'])){
    		echo "您已经投过票了";
    	}else{
	    	Redis::Zadd($key,time(),$openid);
    	}

    	$total=Redis::zCard($key); // 有序集合获取总人数
        echo "<hr>";
        echo "   投票总人数：  ".$total;
        echo "<hr>";

        // 获取用户名称  和时间
    	$numbers=Redis::zRange($key,0,-1,true);
    	// foreach 循环用户
    	foreach ($numbers as $k => $v) {
            $u_k='h:u:'.$k;
            $u=Redis::hGetAll($u_k);
    		// $u = Redis::hMget($u_k,['openid','nickname','sex','headimgurl']);
            echo '<img src="'.$u['headimgurl'].'">';
    	}

        
    	// echo '<pre>'.'用户名称和时间戳：';print_r($numbers);echo '</pre>';

    	// 测试代码
    	// $redis_key='vote';
    	// $number = Redis::incr($redis_key); // incr是increment的缩写 自增添加的意思
    	// echo "投票成功,当前票数".$number;

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

    /**
     * hash 添加值  √
     */
    public function hashTest()
    {
        $uid=1000;
        $key='h:user_info:uid'.$uid;
        $user_info=[
            'uid' => $uid,
            'user_name' => 'xiaobai',
            'email' => 'moni1208@163.com',
            'age' => 19,
            'sex' => 1
        ];

        Redis::hMset($key,$user_info);
        // die;
        echo "<hr>";
        $u=Redis::hGetAll($key);
        echo '<pre>'; print_r($u); echo "</pre>";
    }

}
