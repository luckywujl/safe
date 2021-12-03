<?php

namespace app\admin\model\suisunwechat;

use think\Model;
use traits\model\SoftDelete;

class WechatFansTag extends Model
{

    use SoftDelete;

    

    // 表名
    protected $name = 'suisunwechat_wechat_fans_tag';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = 'deletetime';

    // 追加属性
    protected $append = [

    ];
    

    







}
