<?php
/**
 * Created by PhpStorm.
 * User: JIAN
 * Date: 2020/8/6
 * Time: 21:09
 */
namespace addons\suisunwechat\library\wechat\response;

use app\admin\model\suisunwechat\material\WechatVideo;
use EasyWeChat\Kernel\Messages\Video;

class SourceVideo implements SourceInterface
{

    /**
     * 内容响应
     * @param $content
     * @return mixed
     */
    public function response($content)
    {
        // TODO: Implement response() method.
        $video = WechatVideo::get($content);
        if($video && !empty($video['media_id'])){
            $video_so = new Video($video['media_id'], [
                'title' => $video['name'],
                'description' => $video['desc'],
                'thumb_media_id' => $video['thumb_media_id'],
            ]);
            return $video_so;
        }
        return "";
    }
}