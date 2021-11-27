<?php

namespace addons\training\model;

use think\Model;

class Record extends Model
{

    // 表名
    protected $name = 'training_record';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';

    protected static function init()
    {
      
    }
    public function user()
    {
        return $this->belongsTo('app\common\model\User','user_id')->setEagerlyType(1);
    }
}
