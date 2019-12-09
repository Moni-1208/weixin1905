<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TestController extends Controller
{
	public function hello()
	{
		$md=md5(123456);
		dd($md);
	   	// echo "hello whorld!";
	   	echo "hahhah";
	}

	public function phpinfo()
	{
		phpinfo();
	}


	public function baidu()
	{
		$url='https://learnku.com/docs/laravel/5.8/eloquent/3931';
		$client= new Client();
		$response=$client->request('GET',$url);
		echo $response->getBody();
	}

	public function xmlTest()
	{
		$xml_str='<xml><ToUserName><![CDATA[gh_0080c841f4bb]]></ToUserName>
				<FromUserName><![CDATA[oYtxIt0WcMTSZnseMC_IMOMlXe1M]]></FromUserName>
				<CreateTime>1575892031</CreateTime>
				<MsgType><![CDATA[text]]></MsgType>
				<Content><![CDATA[saaa]]></Content>
				<MsgId>22561348791150048</MsgId>
				</xml>
				';
		$xml_obj=simplexml_load_string($xml_str);
		echo '<pre>'; print_r($xml_obj); echo '</pre>';echo "<hr>";die;
		echo 'ToUserName'.$xml_obj->ToUserName;echo '<br>';
		echo 'FromUserName'.$xml_obj->FromUserName;echo '<br>';
	}

}
