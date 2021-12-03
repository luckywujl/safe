<?php
/**
 * Created by PhpStorm.
 * User: JIAN
 * Date: 2020/8/7
 * Time: 15:12
 */

namespace addons\suisunwechat\library\wechat\message;

interface MessageInterface
{
    /**
     * 内容响应
     * @param $content
     * @return mixed
     */
    public function response($content);
}