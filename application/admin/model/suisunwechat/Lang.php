<?php

namespace app\admin\model\suisunwechat;

use think\Model;


class Lang extends Model
{

    // 表名
    protected $name = 'suisunwechat_lang';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = false;
}
