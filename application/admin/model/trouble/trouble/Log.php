<?php

namespace app\admin\model\trouble\trouble;

use think\Model;


class Log extends Model
{

    

    

    // 表名
    protected $name = 'trouble_log';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'log_time_text'
    ];
    

    



    public function getLogTimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['log_time']) ? $data['log_time'] : '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }

    protected function setLogTimeAttr($value)
    {
        return $value === '' ? null : ($value && !is_numeric($value) ? strtotime($value) : $value);
    }


}
