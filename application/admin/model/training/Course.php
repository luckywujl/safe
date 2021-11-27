<?php

namespace app\admin\model\training;

use think\Model;
use traits\model\SoftDelete;
use addons\training\model\Record;
use app\admin\model\training\Main as MainModel;
class Course extends Model
{

    use SoftDelete;

    // 表名
    protected $name = 'training_course';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = 'deletetime';

    // 追加属性
    protected $append = [
        'uploadtime_text',
        'status_text'
    ];


    public function getStatusList()
    {
        return ['hidden' => __('Hidden'), 'normal' => __('Normal')];
    }

    protected static function init()
    {
        self::afterInsert(function ($row) {
            $pk = $row->getPk();
            $row->getQuery()->where($pk, $row[$pk])->update(['weigh' => $row[$pk]]);
        });

        self::afterDelete(function ($row) {
            Record::destroy(['training_course_id'=>$row['id']]);
        });
    }

    public function getStatusTextAttr($value, $data)
    {
        $value = $value ? $value : $data['status'];
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }

    public function getUploadtimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['uploadtime']) ? $data['uploadtime'] : '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }

    protected function setUploadtimeAttr($value)
    {
        return $value === '' ? null : ($value && !is_numeric($value) ? strtotime($value) : $value);
    }


}
