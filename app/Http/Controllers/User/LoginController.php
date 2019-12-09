<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\UserModel;

class LoginController extends Controller
{
    public function addUser()
    {
    	$pass="123456";
    	// 使用密码函数
    	$password=password_hash($pass,PASSWORD_BCRYPT);
    	// dd($pass);
    	$email="moni1208@163.com";
    	$data = [
    		'user_name' => "小白",
    		'password' => $password,
    		'email' => $email
    	];
    	$res=UserModel::insertGetId($data);
    	var_dump($res);
    }
}
