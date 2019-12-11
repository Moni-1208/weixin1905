<?php

namespace App\Http\Controllers\Weixin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WxController extends Controller
{
    protected $access_token;
    
    public function __construct()
    {
        // 获取access_token
         $this->access_token=$this->getAccessToken();
    }

    protected function getAccessToken()
    {
        $url='https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wxe10b6a253c208edb&secret=0b5c451ab2ee2724d44b177a49bedb4b';
        $data_json=file_get_contents($url); // 返回json类型
        $arr=json_decode($data_json,true);
        return $arr['access_token'];
    }

    /**
     * 微信接口测试
     */
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
        // 接收日志
        $xml_str=file_get_contents("php://input");
        // $data=date('y-m-d h:i:s').json_encode($_POST);
        $data=date('y-m-d h:i:s').$xml_str;
        file_put_contents($log_file,$data,FILE_APPEND); //追加写
        // 处理xml数据
        $xml_obj=simplexml_load_string($xml_str);

        // 入库
        
        // 获取事件的类型
            $event=$xml_obj->Event;   
            if($event=='subscribe'){
                // 获取用户的opendID
                $openid=$xml_obj->FromUserName;
                // 获取用户信息
                $url='https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$this->access_token.'&openid='.$openid.'&lang=zh_CN';
                $user_info=file_get_contents($url); // 返回json数据类型
                file_put_contents('wx_user.log',$user_info,FILE_APPEND);
            }
       

        // 判断消息类型
        $msg_type=$xml_obj->MsgType;
        // 接收消息的用户opendid
        $touser=$xml_obj->FromUserName;
        // 开发者公众号的ID
        $fromuser=$xml_obj->ToUserName;

        $time=time();
        if($msg_type=='text'){
            $content = date('Y-m-d H:i:s') . $xml_obj->Content;
            $response_text = '<xml>
                              <ToUserName><![CDATA['.$touser.']]></ToUserName>
                              <FromUserName><![CDATA['.$fromuser.']]></FromUserName>
                              <CreateTime>'.$time.'</CreateTime>
                              <MsgType><![CDATA[text]]></MsgType>
                              <Content><![CDATA['.$content.']]></Content>
                            </xml>';
            echo $response_text;            // 回复用户消息返回发送过来的文本消息
        }


    }


    /**
     * 获取用户基本信息（openID）
     */
    public function getUserInfo($access_token,$openid)
    {
        // 获取assecc_token:(https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wxe10b6a253c208edb&secret=0b5c451ab2ee2724d44b177a49bedb4b)
        $url='https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN'; //用户基本信息
        // 发送网络请求
        $json_str=file_get_contents($url);
        $simplexml_load_string='wx_user.log';
        file_get_contents($log_file.$json_str,FILE_APPEND);
    }
    public function textinfo(){



        $xml_str='<xml><ToUserName><![CDATA[gh_0080c841f4bb]]></ToUserName>
                        <FromUserName><![CDATA[oYtxIt0WcMTSZnseMC_IMOMlXe1M]]></FromUserName>
                        <CreateTime>1575893487</CreateTime>
                        <MsgType><![CDATA[event]]></MsgType>
                        <Event><![CDATA[subscribe]]></Event>
                        <EventKey><![CDATA[]]></EventKey>
                </xml>';
        $xml_obj=simplexml_load_string($xml_str);
        $openid=$xml_obj->FromUserName;
           $url='https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$this->access_token.'&openid='.$openid.'&lang=zh_CN';
        $data_json=file_get_contents($url); // 返回json类型
        $arr=json_decode($data_json,true);
        dd($arr);exit;

                 
                 $openid=$xml_obj->FromUserName;
                 $access_token=$this->access_token;
                 dd($access_token);
                  $url='https://api.weixin.qq.com/cgi-bin/user/info?access_token='.'&openid='.$openid.'&lang=zh_CN'; //用户基本信息
        // 发送网络请求
            echo $json_str=file_get_contents($url);
    }
    
}
