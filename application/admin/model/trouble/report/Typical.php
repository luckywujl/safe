<?php

namespace app\admin\model\trouble\report;

use think\Model;
//use traits\model\SoftDelete;

class Typical extends Model
{

    //use SoftDelete;

    

    // 表名
    protected $name = 'trouble_main';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
   // protected $deleteTime = 'deletetime';

    // 追加属性
    protected $append = [
        'finishtime_text',
        'source_type_text',
        'main_status_text'
    ];
    

    
    public function getSourceTypeList()
    {
        return ['0' => __('Source_type 0'), '1' => __('Source_type 1'), '2' => __('Source_type 2')];
    }

    public function getMainStatusList()
    {
        return ['0' => __('Main_status 0'), '1' => __('Main_status 1'), '2' => __('Main_status 2'), '3' => __('Main_status 3'), '4' => __('Main_status 4'), '5' => __('Main_status 5'), '6' => __('Main_status 6'), '7' => __('Main_status 7'), '8' => __('Main_status 8'), '9' => __('Main_status 9')];
    }


    public function getFinishtimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['finishtime']) ? $data['finishtime'] : '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }


    public function getSourceTypeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['source_type']) ? $data['source_type'] : '');
        $list = $this->getSourceTypeList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getMainStatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['main_status']) ? $data['main_status'] : '');
        $list = $this->getMainStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }

    protected function setFinishtimeAttr($value)
    {
        return $value === '' ? null : ($value && !is_numeric($value) ? strtotime($value) : $value);
    }


    public function troublepoint()
    {
        return $this->belongsTo('app\admin\model\trouble\base\Point', 'point_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }


    public function troubletype()
    {
        return $this->belongsTo('app\admin\model\trouble\base\Type', 'trouble_type_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }
}
