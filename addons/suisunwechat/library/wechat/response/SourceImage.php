<?php
/**
 * Created by PhpStorm.
 * User: JIAN
 * Date: 2020/8/6
 * Time: 21:09
 */
namespace addons\suisunwechat\library\wechat\response;

use app\admin\model\suisunwechat\material\WechatImage;
use EasyWeChat\Kernel\Messages\Image;
use think\Log;

class SourceImage implements SourceInterface
{

    /**
     * 内容响应
     * @param $content
     * @return mixed
     */
    public function response($content)
    {
        // TODO: Implement response() method.
        $image = WechatImage::get($content);
        if($image && !empty($image['media_id'])){
            return new Image($image['media_id']);
        }
        return "";
    }
}