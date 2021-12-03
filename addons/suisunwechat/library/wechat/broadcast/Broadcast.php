<?php
/**
 * Created by PhpStorm.
 * User: JIAN
 * Date: 2020/8/6
 * Time: 20:58
 */
namespace addons\suisunwechat\library\wechat\broadcast;

class Broadcast
{

    /**
     * 响应资源
     * @var BroadcastInterface
     */
    protected $instance;

    protected static $map = [
        'text'          => BroadcastText::class,
        'textandphoto'  => BroadcastImageAndPhoto::class,
        'voice'         => BroadcastVoice::class,
        'image'         => BroadcastImage::class,
        'video'         => BroadcastVideo::class,
        'article'       => BroadcastImageAndPhoto::class,
    ];



    /**
     * @param string $type
     * @return  BroadcastInterface|bool
     */
    public static function instance($type = 'text'){
        if(array_key_exists($type,self::$map)){
            return new self::$map[$type];
        }
        return false;
    }

}