<?php

namespace addons\training\model;

use think\Model;
use addons\training\model\Record as RecordModel;
use traits\model\SoftDelete;

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
        'status_text',
        'progress',
        'complete'
    ];

    protected static function init()
    {
        self::afterInsert(function ($row) {
            $pk = $row->getPk();
            $row->getQuery()->where($pk, $row[$pk])->update(['weigh' => $row[$pk]]);
        });

        self::afterWrite(function ($row) {
            $pk = $row->getPk();
            $row->course();
            $duration = 0;
            foreach ($row as $item){
                $duration += $item['duration'];
            }
            $row->getQuery()->where($pk, $row[$pk])->update(['duration' => $duration]);
        });

    }
    public function getProgressAttr($value, $data)
    {
        $user_id = \app\common\library\Auth::instance()->id;
        $arr = RecordModel::where('user_id',$user_id)->where('training_main_id','in',$data['id'])->where('training_course_id','in',$data['training_course_ids'])->column('progress');
        $sum = 0;
        $progress = 0;
        $count = count(explode(",", $data['training_course_ids']));
        if($count>0){
            foreach ($arr as $key => $value) {
                $sum+=$value;
            }
            $progress = round($sum/$count,0);
        }
        
        return $progress;
    }
    public function getCompleteAttr($value, $data)
    {
        $user_id = \app\common\library\Auth::instance()->id;
        $arr = RecordModel::where('user_id',$user_id)->where('training_main_id','in',$data['id'])->where('training_course_id','in',$data['training_course_ids'])->column('complete');
        $sum = 0;
        $complete = 0;
        $count = count(explode(",", $data['training_course_ids']));
        if($count>0){
            foreach ($arr as $key => $value) {
                $sum+=$value;
            }
            $complete = $sum/$count==1?1:0;
        }
        
        return $complete;
    }

    public function getStatusList()
    {
        return ['hidden' => __('Hidden'), 'normal' => __('Normal')];
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

    public function course()
    {
        return $this->hasMany('Course','id','training_course_ids');
    }
}
