<?php
/**
 * Created by PhpStorm.
 * User: JIAN
 * Date: 2020/8/6
 * Time: 21:09
 */
namespace addons\suisunwechat\library\wechat\response;

use app\admin\model\suisunwechat\material\WechatVoice;
use EasyWeChat\Kernel\Messages\Voice;
use think\Log;

class SourceVoice implements SourceInterface
{

    /**
     * 内容响应
     * @param $content
     * @return mixed
     */
    public function response($content)
    {
        // TODO: Implement response() method.
        $voice = WechatVoice::get($content);
        if($voice && !empty($voice['media_id'])){
            return new Voice($voice['media_id']);
        }
        return "";
    }
}