<?php

namespace app\admin\model\training;

use think\Model;
use traits\model\SoftDelete;
use addons\training\model\Record;
use app\admin\model\training\Course;
use addons\training\model\Result;

class Main extends Model
{

    use SoftDelete;

    // 表名
    protected $name = 'training_main';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = 'deletetime';

    // 追加属性
    protected $append = [
        'starttime_text',
        'endtime_text',
        'status_text'
    ];

    protected static function init()
    {
        self::afterInsert(function ($row) {
            $pk = $row->getPk();
            $row->getQuery()->where($pk, $row[$pk])->update(['weigh' => $row[$pk]]);
        });

        self::afterWrite(function ($row) {
            $pk = $row->getPk();
            $course = Course::where('id','in',$row['training_course_ids'])->select();
            $duration = 0;
            foreach ($course as $key => $value) {
                $duration += (int)$value['duration'];
            }
            $row->getQuery()->where($pk, $row[$pk])->update(['duration' => $duration]);
        });
        
        self::beforeUpdate(function($row){
            $pk = $row->getPk();
            $old = $row->getQuery()->where($pk, $row[$pk])->value('training_course_ids');
            $a1=explode(',', $old);
            $a2=explode(',', $row['training_course_ids']);
            $result=array_diff($a1,$a2);
            if($result){
                Result::destroy(['training_main_id'=>$row['id']]);
            }
        });

        self::afterDelete(function ($row) {
            Record::destroy(['training_main_id'=>$row['id']]);
            Result::destroy(['training_main_id'=>$row['id']]);
            Course::destroy(function($query) use($row){
                $query->where('id','in',$row['training_course_ids']);
            });
        });
    }

    public function getStatusList()
    {
        return ['hidden' => __('Hidden'), 'normal' => __('Normal')];
    }
    public function getTypeList()
    {
        return ['online' => __('Online'), 'offline' => __('Offline')];
    }

    public function getStatusTextAttr($value, $data)
    {
        $value = $value ? $value : $data['status'];
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }

    public function getStarttimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['starttime']) ? $data['starttime'] : '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }

    protected function setStarttimeAttr($value)
    {
        return $value === '' ? null : ($value && !is_numeric($value) ? strtotime($value) : $value);
    }
    
    public function getEndtimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['endtime']) ? $data['endtime'] : '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }

    protected function setEndtimeAttr($value)
    {
        return $value === '' ? null : ($value && !is_numeric($value) ? strtotime($value) : $value);
    }
}
