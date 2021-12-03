<?php
/**
 * Created by PhpStorm.
 * User: JIAN
 * Date: 2018/10/31
 * Time: 18:37
 */

namespace addons\suisunwechat\utils;


class JsonUtil
{
    static function  encode($info){
        return json_encode($info,JSON_UNESCAPED_UNICODE);
    }

    static function decode($info){
        return json_decode($info,true);
    }
}