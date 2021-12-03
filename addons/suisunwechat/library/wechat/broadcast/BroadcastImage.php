<?php
/**
 * Created by PhpStorm.
 * User: JIAN
 * Date: 2020/8/6
 * Time: 21:09
 */
namespace addons\suisunwechat\library\wechat\broadcast;

use app\admin\model\suisunwechat\material\WechatImage;
use EasyWeChat\Kernel\Messages\Image;

class BroadcastImage implements BroadcastInterface
{

    /**
     * 发送消息
     * @param $content
     * @return mixed
     */
    public function all($content){
        $wechat = new \addons\suisunwechat\library\Wechat('wxOfficialAccount');
        $image = WechatImage::get($content);
        if (empty($image)){
            $res = ['errcode' => 1,'errmsg' => '资源不存在'];
            return $res;
        }
        $res = $wechat->getApp()->broadcasting->sendImage($image->media_id);
        return $res;
    }

    public function user($content,$user){
        $wechat = new \addons\suisunwechat\library\Wechat('wxOfficialAccount');
        $image = WechatImage::get($content);
        if (empty($image)){
            $res = ['errcode' => 1,'errmsg' => '资源不存在'];
            return $res;
        }
        $res = $wechat->getApp()->broadcasting->sendImage($image->media_id,$user);
        return $res;
    }

    public function tag($content,$tag){
        $wechat = new \addons\suisunwechat\library\Wechat('wxOfficialAccount');
        $image = WechatImage::get($content);
        if (empty($image)){
            $res = ['errcode' => 1,'errmsg' => '资源不存在'];
            return $res;
        }
        $res = $wechat->getApp()->broadcasting->sendImage($image->media_id,$tag);
        return $res;
    }

    public function preview($content,$openid){
        $wechat = new \addons\suisunwechat\library\Wechat('wxOfficialAccount');
        $image = WechatImage::get($content);
        if (empty($image)){
            $res = ['errcode' => 1,'errmsg' => '资源不存在'];
            return $res;
        }
        $res = $wechat->getApp()->broadcasting->previewImage($image->media_id,$openid);
        return $res;
    }
}