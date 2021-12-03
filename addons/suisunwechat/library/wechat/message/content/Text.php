<?php
/**
 * Created by PhpStorm.
 * User: JIAN
 * Date: 2020/8/7
 * Time: 15:14
 */

namespace addons\suisunwechat\library\wechat\message\content;


use addons\suisunwechat\library\wechat\message\MessageInterface;
use addons\suisunwechat\library\wechat\response\SourceResponse;
use app\admin\model\suisunwechat\Source;
use app\admin\model\suisunwechat\WechatAutoReply;
use think\Log;

class Text implements MessageInterface
{

    /**
     * 内容响应
     * @param $content
     * @return mixed
     */
    public function response($content)
    {
        // TODO: Implement response() method.
        $autoreply = null;
        $autoreplyList = WechatAutoReply::cache(true,5)->select();
        foreach ($autoreplyList as $index => $item) {
            //完全匹配和正则匹配
            if ($item['keywordcontent'] == $content || (in_array(mb_substr($item['keywordcontent'], 0, 1), ['#', '~', '/']) && preg_match($item['keywordcontent'], $content, $matches))) {
                $autoreply = $item;
                break;
            }
        }
        if ($autoreply) { //响应用户
            $source = Source::where(["eventkey" => $autoreply['eventkey'], 'status' => 1])->find(); //资源响应
            Log::write($source);
            if ($source) {
                $response = SourceResponse::instance($source['type']);
                if($response){
                    return $response->response($source['content']);
                }
            }
        }
        return "";
    }
}