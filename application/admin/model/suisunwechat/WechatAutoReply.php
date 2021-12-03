<?php

namespace app\admin\model\suisunwechat;

use think\Model;


class WechatAutoReply extends Model
{

    

    

    // 表名
    protected $name = 'suisunwechat_auto_reply';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = false;

    // 追加属性
    protected $append = [

    ];


    public function events(){
        return $this->belongsTo(Source::class,'eventkey','eventkey');
    }

    







}
