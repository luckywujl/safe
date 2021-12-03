<?php

namespace app\admin\model\suisunwechat;

use addons\shopro\model\UserOauth;
use think\Model;

class ServiceHistory  extends Model
{

    // 表名
    protected $name = 'suisunwechat_service_history';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
    ];

    public function user(){
        return $this->belongsTo(UserOauth::class,'uopenid','openid');
    }

    public function admin(){
        return $this->belongsTo(UserOauth::class,'aopenid','openid');
    }
}
