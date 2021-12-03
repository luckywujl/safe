<?php

namespace app\admin\model\kaoshi\examination;

use think\Model;


class Kaoshirecord extends Model
{

    

    

    // 表名
    protected $name = 'kaoshi_user_plan';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'status_text'
    ];
    

    
    public function getStatusList()
    {
        return ['0' => __('Status 0'), '1' => __('Status 1')];
    }


    public function getStatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['status']) ? $data['status'] : '');
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }




    public function kaoshiuserexams()
    {
        return $this->belongsTo('app\admin\model\kaoshi\examination\KaoshiUserExams', 'id', 'user_plan_id', [], 'LEFT')->setEagerlyType(0);
    }


    public function kaoshiplan()
    {
        return $this->belongsTo('app\admin\model\kaoshi\examination\KaoshiPlan', 'plan_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }
    
    public function kaoshisubject()
    {
        return $this->belongsTo('app\admin\model\kaoshi\KaoshiSubject', 'plan_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }
}
