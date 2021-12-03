<?php
/**
 * Created by PhpStorm.
 * User: JIAN
 * Date: 2020/8/6
 * Time: 21:06
 */

namespace addons\suisunwechat\library\wechat\response;

interface  SourceInterface
{

    /**
     * 内容响应
     * @param $content
     * @return mixed
     */
    public function response($content);

}