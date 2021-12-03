<?php

namespace app\admin\model\suisunwechat;

use think\Model;


class WechatFans extends Model
{

    

    

    // 表名
    protected $name = 'suisunwechat_wechat_fans';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'subscribe_text',
        'subscribe_time_text'
    ];
    

    
    public function getSubscribeList()
    {
        return ['0' => __('Subscribe 0'), '1' => __('Subscribe 1')];
    }


    public function getSubscribeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['subscribe']) ? $data['subscribe'] : '');
        $list = $this->getSubscribeList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getSubscribeTimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['subscribe_time']) ? $data['subscribe_time'] : '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }

    protected function setSubscribeTimeAttr($value)
    {
        return $value === '' ? null : ($value && !is_numeric($value) ? strtotime($value) : $value);
    }


}
