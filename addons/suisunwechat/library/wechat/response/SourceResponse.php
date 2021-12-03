<?php
/**
 * Created by PhpStorm.
 * User: JIAN
 * Date: 2020/8/6
 * Time: 20:58
 */
namespace addons\suisunwechat\library\wechat\response;

class SourceResponse
{

    /**
     * 响应资源
     * @var SourceInterface
     */
    protected $instance;

    protected static $map = [
        'text'          => SourceText::class,
        'textandphoto'  => SourceImageAndPhoto::class,
        'audio'         => SourceVoice::class,
        'image'         => SourceImage::class,
        'video'         => SourceVideo::class,
    ];



    /**
     * @param string $type
     * @return  SourceInterface|bool
     */
    public static function instance($type = 'text'){
        if(array_key_exists($type,self::$map)){
            return new self::$map[$type];
        }
        return false;
    }

}