<?php

namespace app\admin\model\training;

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
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'studytime_text'
    ];
    

    



    public function getStudytimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['studytime']) ? $data['studytime'] : '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }

    protected function setStudytimeAttr($value)
    {
        return $value === '' ? null : ($value && !is_numeric($value) ? strtotime($value) : $value);
    }


    public function trainingcourse()
    {
        return $this->belongsTo('app\admin\model\training\Course', 'training_course_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }


    public function trainingmain()
    {
        return $this->belongsTo('app\admin\model\training\Main', 'training_main_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }
}
