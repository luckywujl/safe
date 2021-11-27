<?php

namespace addons\training\model;

use think\Model;

class Result extends Model
{

    // 表名
    protected $name = 'training_main_log';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    
    protected static function init()
    {
      
    }
}
