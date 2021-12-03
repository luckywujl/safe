<?php
/**
 * Created by PhpStorm.
 * User: JIAN
 * Date: 2020/8/6
 * Time: 21:09
 */
namespace addons\suisunwechat\library\wechat\broadcast;


class BroadcastText implements BroadcastInterface
{

    /**
     * 发送消息
     * @param $content
     * @return mixed
     */
    public function all($content){
        $wechat = new \addons\suisunwechat\library\Wechat('wxOfficialAccount');
        $res = $wechat->getApp()->broadcasting->sendText($content);
        return $res;
    }

    public function user($content,$user){
        $wechat = new \addons\suisunwechat\library\Wechat('wxOfficialAccount');
        $res = $wechat->getApp()->broadcasting->sendText($content,$user);
        return $res;
    }

    public function tag($content,$tag){
        $wechat = new \addons\suisunwechat\library\Wechat('wxOfficialAccount');
        $res = $wechat->getApp()->broadcasting->sendText($content,$tag);
        return $res;
    }

    public function preview($content,$openid){
        $wechat = new \addons\suisunwechat\library\Wechat('wxOfficialAccount');
        $res = $wechat->getApp()->broadcasting->previewText($content,$openid);
        return $res;
    }
}