<?php

namespace app\admin\model\suisunwechat;

use addons\suisunwechat\utils\JsonUtil;
use think\Model;


class Wechat extends Model
{

    // 表名
    protected $name = 'suisunwechat_wechat';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = false;

    const TYPE_BASE = 'base';
    const TYPE_CONDITIONAL = 'conditional';

    // 追加属性
    protected $append = [

    ];


    public function getTypeList()
    {
        return ['base' => __('Type base'), 'conditional' => __('Type conditional')];
    }


    public function getTypeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['type']) ? $data['type'] : '');
        $list = $this->getTypeList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getMatchruleAttr($value,$data){
        $param = ['tag_id' => '', 'sex' => '', 'client_platform_type' => '', 'language' => ''];
        if($data['type']==self::TYPE_BASE){
            return $param;
        }
        $list = JsonUtil::decode($value);
        $list = array_merge($param,$list);
        return $list;
    }
}
