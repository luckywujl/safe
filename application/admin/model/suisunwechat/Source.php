<?php

namespace app\admin\model\suisunwechat;

use fast\Random;
use think\Model;
use traits\model\SoftDelete;

class Source extends Model
{

    use SoftDelete;

    

    // 表名
    protected $name = 'suisunwechat_wechat_source';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = 'deletetime';

    protected $insert = ['eventkey'];


    const TYPE_TEXT = 'text';
    const TYPE_TEXTANDPHOTO = 'textandphoto';

    // 追加属性
    protected $append = [
        'type_text',
        'status_text'
    ];


    /**
     * 事件
     * @param $value
     * @param $data
     */
    public function setEventkeyAttr($value,$data){
        $eventkey = Random::alpha(10);
        while (true){
            $source = Source::get(['eventkey'=>$eventkey]);
            if($source){
                $eventkey = Random::alpha(10);
            }
            break;
        }
        return $eventkey;
    }
    

    
    public function getTypeList()
    {
        return [
            'text'         => __('Type text'),
            'textandphoto' => __('Type textandphoto'),
            'video'        => __('Type video'),
            'audio'        => __('Type audio'),
            'image'        => __('Type image'),
        ];
    }

    public function getStatusList()
    {
        return ['0' => __('Status 0'), '1' => __('Status 1')];
    }


    public function getTypeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['type']) ? $data['type'] : '');
        $list = $this->getTypeList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getStatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['status']) ? $data['status'] : '');
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }




}
