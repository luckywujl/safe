<?php

namespace addons\trouble\model;

use think\Model;


class Point extends Model
{

    

    

    // 表名
    protected $name = 'trouble_point';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [

    ];
    

    







    public function troublearea()
    {
        return $this->belongsTo('addons\trouble\model\Area', 'point_area_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }


    public function userdepartment()
    {
        return $this->belongsTo('addons\trouble\model\Department', 'point_department_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }
}
