<?php

namespace App\Http\Controllers\Weixin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Model\WxUserModel;
use Illuminate\Support\Facades\Redis;

use GuzzleHttp\Client;

class WxController extends Controller
{
    protected $access_token;
    public function __construct()
    {
        //获取 access_token
        $this->access_token = $this->getAccessToken();
    }
    protected function getAccessToken()
    {
        $key='wx_access_token';
        $access_token=Redis::get($key);
        // var_dump($access_token);
        $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wxe10b6a253c208edb&secret=0b5c451ab2ee2724d44b177a49bedb4b';
        $data_json = file_get_contents($url);
        $arr = json_decode($data_json,true);

        Redis::set($key,$arr['access_token']);
        Redis::expire($key,3600);

        return $arr['access_token'];
    }
    /**
     * 处理接入
     */
    public function wechat()
    {
        $token = 'qwertyuiopzxcvbnm';       //开发提前设置好的 token
        $signature = $_GET["signature"];
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
     * 接收微信推送事件
     */
    public function receiv()
    {
        $log_file = "wx.log";       // public
        //将接收的数据记录到日志文件
        $xml_str = file_get_contents("php://input");
        $data = date('Y-m-d H:i:s')  . ">>>>>>\n" . $xml_str . "\n\n";
        file_put_contents($log_file,$data,FILE_APPEND);     //追加写
        //处理xml数据
        $xml_obj = simplexml_load_string($xml_str);
        $event = $xml_obj->Event;       // 获取事件类型
        if($event=='subscribe'){
            $openid = $xml_obj->FromUserName;       //获取用户的openid
            //判断用户是否已存在
            $u = WxUserModel::where(['openid'=>$openid])->first();
            // echo $u;die;
            if($u){
                $msg = '欢迎回来';
                $xml = '<xml>
  <ToUserName><![CDATA['.$openid.']]></ToUserName>
  <FromUserName><![CDATA['.$xml_obj->ToUserName.']]></FromUserName>
  <CreateTime>'.time().'</CreateTime>
  <MsgType><![CDATA[text]]></MsgType>
  <Content><![CDATA['.$msg.']]></Content>
</xml>';
                echo $xml;
            }else{
                //获取用户信息 zcza
                $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$this->access_token.'&openid='.$openid.'&lang=zh_CN';
                // echo $url;die;
                $user_info = file_get_contents($url);       //
                $u = json_decode($user_info,true);
                //echo '<pre>';print_r($u);echo '</pre>';die;
                //入库用户信息
                $user_data = [
                    'openid'    => $openid,
                    'nickname'  => $u['nickname'],
                    'sex'       => $u['sex'],
                    'headimgurl'    => $u['headimgurl'],
                    'subscribe_time'    => $u['subscribe_time']
                ];
                //openid 入库
                $uid = WxUserModel::insertGetId($user_data);
                $msg = "谢谢关注";
                //回复用户关注
                $xml = '<xml>
  <ToUserName><![CDATA['.$openid.']]></ToUserName>
  <FromUserName><![CDATA['.$xml_obj->ToUserName.']]></FromUserName>
  <CreateTime>'.time().'</CreateTime>
  <MsgType><![CDATA[text]]></MsgType>
  <Content><![CDATA['.$msg.']]></Content>
</xml>';
                echo $xml;
            }
        }
        // 判断消息类型
        $msg_type = $xml_obj->MsgType;
        echo $msg_type;die;
        $touser = $xml_obj->FromUserName;       //接收消息的用户openid
        $fromuser = $xml_obj->ToUserName;       // 开发者公众号的 ID
        $time = time();

        $media_id=$xml_obj->MediaId;
        // dd($media_id);die;
        if($msg_type=='text'){
            $content = date('Y-m-d H:i:s') . $xml_obj->Content;
            $response_text = '<xml>
  <ToUserName><![CDATA['.$touser.']]></ToUserName>
  <FromUserName><![CDATA['.$fromuser.']]></FromUserName>
  <CreateTime>'.$time.'</CreateTime>
  <MsgType><![CDATA[text]]></MsgType>
  <Content><![CDATA['.$content.']]></Content>
</xml>';
            // 回复用户消息
            echo $response_text;

            // TODO 消息入库
        }elseif($msg_type=='image'){  //图片消息
            // TODO 下载图片
            $this->getMedia2($media_id,$msg_type);
            // TODO  回复图片
            $response='<xml>
  <ToUserName><![CDATA['.$touser.']]></ToUserName>
  <FromUserName><![CDATA['.$fromuser.']]></FromUserName>
  <CreateTime>'.time().'</CreateTime>
  <MsgType><![CDATA[image]]></MsgType>
  <Image>
    <MediaId><![CDATA['.$media_id.']]></MediaId>
  </Image>
</xml>';
            echo $response;
        }elseif ($msg_type=='voice  ') {  // 语音消息
            // TODO  下载语音
            $this->getMedia2($media_id,$msg_type);
            // TODO  回复语音
            $response='<xml>
  <ToUserName><![CDATA['.$touser.']]></ToUserName>
  <FromUserName><![CDATA['.$fromuser.']]></FromUserName>
  <CreateTime>'.time().'</CreateTime>
  <MsgType><![CDATA[voice]]></MsgType>
  <Image>
    <MediaId><![CDATA['.$media_id.']]></MediaId>
  </Image>
</xml>';
            echo $response;
        }elseif ($msg_type=='video') {  // 视频消息
            // TODO  下载小视频
            $this->getMedia2($media_id,$msg_type);
            // TODO  回复小视频
            $response='<xml>
  <ToUserName><![CDATA['.$touser.']]></ToUserName>
  <FromUserName><![CDATA['.$fromuser.']]></FromUserName>
  <CreateTime>'.time().'</CreateTime>
  <MsgType><![CDATA[video]]></MsgType>
  <Image>
    <MediaId><![CDATA['.$media_id.']]></MediaId>
  </Image>
</xml>';
            echo $response;
        }
    }
    /**
     * 获取用户基本信息
     */
    public function getUserInfo($access_token,$openid)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
        //发送网络请求
        $json_str = file_get_contents($url);
        $log_file = 'wx_user.log';
        file_put_contents($log_file,$json_str,FILE_APPEND);
    }


    /**
     * 图片 获取素材
     */
    public function getMedia()
    {
        $media_id = 'oYtxIt0WcMTSZnseMC_IMOMlXe1M';
        $url ='https://api.weixin.qq.com/cgi-bin/media/get?access_token='.$this->access_token.'&media_id='.$media_id;
        // 下载图片
        $img = file_get_contents($url);
        // 保存图片
        $file_name=date('YmdHis').mt_rand(1111,9999).'.amr';
        file_put_contents($file_name, $img);
        echo "图片下载成功";
        echo "文件名：".$file_name;
    }

    /**
     * 获取素材 
     */
    public function getMedia2($media_id,$msg_type)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/media/get?access_token='.$this->access_token.'&media_id='.$media_id;
        // 获取素材内容
        // echo $url;die;
        $client = new Client();
        // dd($client);die;
        $response=$client->request('GET',$url);
        // dd($response);die;
        // 获取文件 扩展 名
        $f = $response->getHeader('Content-disposition')[0];
        $extension = substr(trim($f,'"'),strpos($f,'.'));
        // 获取文件内容 
        $file_content=$response->getBody();

        // 保存文件
        $save_path = 'wx_media/';
        if($msg_type=='image'){       //保存图片文件
            $file_name = date('YmdHis').mt_rand(11111,99999) . $extension;
            $save_path = $save_path . 'imgs/' . $file_name;
        }elseif($msg_type=='voice'){  //保存语音文件
            $file_name = date('YmdHis').mt_rand(11111,99999) . $extension;
            $save_path = $save_path . 'voice/' . $file_name;
        }elseif($msg_type=='video')
        {
            $file_name = date('YmdHis').mt_rand(11111,99999) . $extension;
            $save_path = $save_path . 'video/' . $file_name;
        }
        file_put_contents($save_path,$file_content);
        echo "文件保存成功：".$save_path;
    }
    


    //刷新access_token  成功
    public function flushAccessToken()
    {
        $key ='wx_access_token';
        Redis::del($key);
        echo $this->getAccessToken();
    }

    /**
     * 创建自定义菜单
     */
    public function createMenu()
    {

    }

    
}
