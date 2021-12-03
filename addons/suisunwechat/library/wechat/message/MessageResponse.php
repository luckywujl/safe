<?php
/**
 * Created by PhpStorm.
 * User: JIAN
 * Date: 2020/8/7
 * Time: 15:13
 */
namespace addons\suisunwechat\library\wechat\message;

use addons\suisunwechat\library\wechat\message\content\Other;
use addons\suisunwechat\library\wechat\message\content\Text;

class MessageResponse
{
    /**
     * 响应资源
     * @var MessageInterface
     */
    protected $instance;

    protected static $map = [
        'text'=>Text::class,
        'other'=>Other::class,
    ];



    /**
     * @param string $type
     * @return  MessageInterface
     */
    public static function instance($type = 'text'){
        if(!array_key_exists($type,self::$map)){
            $type = 'other';//如果信息不存在,走其他
        }
        return new self::$map[$type];
    }
}