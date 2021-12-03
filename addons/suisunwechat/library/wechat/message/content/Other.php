<?php
/**
 * Created by PhpStorm.
 * User: JIAN
 * Date: 2020/8/7
 * Time: 15:15
 */

namespace addons\suisunwechat\library\wechat\message\content;



use addons\suisunwechat\library\wechat\message\MessageInterface;
use addons\suisunwechat\library\wechat\response\SourceResponse;
use app\admin\model\suisunwechat\Source;
use app\admin\model\suisunwechat\WechatConfig;
use think\Log;

class Other implements MessageInterface
{

    /**
     * 内容响应
     * @param $content
     * @return mixed
     */
    public function response($content)
    {
        // TODO: Implement response() method.
        //没有匹配信息的时候
        $reply = WechatConfig::getDefaultReply();
        Log::write("读取其他信息");
        Log::write($reply);
        $source = Source::where(["eventkey" => $reply['key'], 'status' => 1])->find(); //资源响应
        if ($source) {
            $response = SourceResponse::instance($source['type']);
            if($response){
                return $response->response($source['content']);
            }
        }
        return "";
    }
}