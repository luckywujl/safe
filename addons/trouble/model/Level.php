<?php

namespace addons\trouble\model;

use think\Model;


class Level extends Model
{

    

    

    // 表名
    protected $name = 'trouble_Level';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [

    ];


}
