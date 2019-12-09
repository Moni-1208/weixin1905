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
}
