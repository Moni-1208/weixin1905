<?php
namespace App\Admin\Controllers;
use App\Model\WxUserModel;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use GuzzleHttp\Client;
class WxMsgController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '微信用户管理';
    public function sendMsg()
    {
        echo __METHOD__;
        $openid_arr = WxUserModel::select('openid','nickname','sex')->get()->toArray();
        //echo '<pre>';print_r($openid_arr);echo '</pre>';
        $openid = array_column($openid_arr,'openid');
        echo '<pre>';print_r($openid);echo '</pre>';
        $url = 'https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token=28_j8w_aW55oyhpJFadg3ovE0T7Bh0YcjLHn9TOr-U1z9Sn90lHK_bUqkmu7hvpHIMWl-RaQXNoYqvUOnTVxLE2WevTl2eN_ULQp5G2ZSDb8tS7URECv2Q2FU_pHoPTACnM3Yj6tOor4KZpu3R0CJJfAIAJQQ';
        $msg = date('Y-m-d H:i:s') . '快要放寒假了，准备好寒假作业了吗';
        $data = [
            'touser'    => $openid,
            'msgtype'   => 'text',
            'text'      => ['content'=>$msg]
        ];
        $client = new Client();
        $response = $client->request('POST',$url,[
            'body'  => json_encode($data,JSON_UNESCAPED_UNICODE)
        ]);
        echo $response->getBody();
    }
}