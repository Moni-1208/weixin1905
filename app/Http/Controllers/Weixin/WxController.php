<?php

namespace App\Http\Controllers\Weixin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WxController extends Controller
{
    // 微信接口测试
        public function wechat()
    {
        $token = 'qwertyuiopzxcvbnm';       //开发提前设置好的 token
        $signature = $_GET["signature"]; //报错
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        $echostr = $_GET["echostr"];


        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );


        if( $tmpStr == $signature ){        //验证通过
            echo $echostr;
        }else{
            die("not ok");
        }
    }


    /**
     * 接受微信推送事件
     */
    public function receiv()
    {
        // 将接收的数据记录到日志文件
        $log_file="wx.log";
        $data=json_encode($_POST);
        file_put_contents($log_file,$data,FILE_APPEND); //追加写
    }


    /**
     * 获取用户基本信息（openID）
     */
    public function openID()
    {
        // 获取assecc_token:(https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wxe10b6a253c208edb&secret=0b5c451ab2ee2724d44b177a49bedb4b)
        $openID="https://api.weixin.qq.com/cgi-bin/user/info?access_token=28_DwDxtY9cyoMRfRLFxITJwF4vPIBG5-gH51BYIuVXUMx4Pri6gL8B-FcYZMJm8CCvEXfn3ldS1JXSVM82GQLRTALhhqCVTAoU7oku3MRDvcbbCdCWeaZ07h63GVgCNGcACAYJC&openid=oYtxIt0WcMTSZnseMC_IMOMlXe1M&lang=zh_CN"; //用户基本信息
        // dd($openid);
    }
    
}
