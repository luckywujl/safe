<?php
/**
 * Created by PhpStorm.
 * User: JIAN
 * Date: 2020/8/6
 * Time: 21:10
 */

namespace addons\suisunwechat\library\wechat\response;


use app\admin\model\suisunwechat\material\WechatImageText;
use addons\suisunwechat\utils\JsonUtil;
use EasyWeChat\Kernel\Messages\News;
use EasyWeChat\Kernel\Messages\NewsItem;

class SourceImageAndPhoto implements SourceInterface
{

    /**
     * 内容响应
     * @param $content
     * @return mixed
     */
    public function response($content)
    {
        // TODO: Implement response() method. 文章消息响应
        $imagetext = WechatImageText::get($content);
        if($imagetext && !empty($imagetext['items'])){
            $items = JsonUtil::decode($imagetext['items']);
            foreach ($items as &$item){
                $item = new NewsItem($item);
            }
            unset($item);
            return  new News($items);
        }
        return "";
    }
}