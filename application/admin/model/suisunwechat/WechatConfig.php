<?php

namespace app\admin\model\suisunwechat;

use addons\suisunwechat\utils\JsonUtil;
use think\Model;


class WechatConfig extends Model
{

    // 表名
    protected $name = 'suisunwechat_wechat_config';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = false;


    const TYPE_ATTENTION_REPLY = 'attention_reply';
    const TYPE_DEfAULT_REPLY = 'default_reply';


    /**
     * 当前配置类型
     * @return array
     */
    public static function getTypeList(){
        return ['attention_reply','default_reply'];
    }


    /**
     * 获取首次回复配置
     * @return array|mixed
     */
    public static function getAttentionReply(){
        $config =  self::getwecahtconfig(self::TYPE_ATTENTION_REPLY);
        $defalut = ['key'=>'','name'=>''];
        $config = array_merge($defalut,$config);
        return $config;
    }

    /**
     * 获取默认回复配置
     * @return array|mixed
     */
    public static function getDefaultReply(){
        $config =  self::getwecahtconfig(self::TYPE_DEfAULT_REPLY);
        $defalut = ['key'=>'','name'=>''];
        $config = array_merge($defalut,$config);
        return $config;
    }


    /**
     * 获取配置
     * @param $type
     * @return array|mixed
     */
    protected static function getwecahtconfig($type){
        $config = self::where(['type'=>$type])->value('config');
        if(empty($config)){
            $config = [];
        }else{
            $config = JsonUtil::decode($config);
        }
        return $config;
    }


}
