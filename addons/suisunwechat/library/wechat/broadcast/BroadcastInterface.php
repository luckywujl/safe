<?php

namespace addons\suisunwechat\library\wechat\broadcast;

interface  BroadcastInterface
{

    /**
     * 发送消息
     * @param $content
     * @return mixed
     */
    public function all($content);

    public function user($content,$user);

    public function tag($content,$tag);

    public function preview($content,$openid);
}