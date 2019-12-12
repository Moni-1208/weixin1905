<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class WxUserModel extends Model
{
	// 数据库名称
    protected $table = 'p_wx_users';
    // 设置主键
    protected $primaryKey  = 'uid';
}
