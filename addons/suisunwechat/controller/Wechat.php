<?php

namespace addons\suisunwechat\controller;

use addons\suisunwechat\library\wechat\message\MessageResponse;
use addons\suisunwechat\library\wechat\response\SourceResponse;
use app\admin\model\suisunwechat\ServiceAdmin;
use app\admin\model\suisunwechat\ServiceLog;
use app\admin\model\suisunwechat\Source;
use app\admin\model\suisunwechat\WechatConfig;
use app\common\controller\Api;
use EasyWeChat\Kernel\Messages\Text;
use think\Db;
use think\Exception;
use think\Log;

/**
 * 微信接口
 */
class Wechat extends Api
{
    protected $noNeedLogin = ['*'];
    protected $noNeedRight = ['*'];
    protected $noNeedActive = ['*'];

    public $app = null;


    public function jssdk()
    {
        $params = $this->request->post();
        $apis = [
            'checkJsApi',
            'onMenuShareTimeline',
            'onMenuShareAppMessage',
            'getLocation',//获取位置
            'openLocation',//打开位置
            'scanQRCode',//扫一扫接口
            'chooseWXPay',//微信支付
            'chooseImage',//拍照或从手机相册中选图接口
            'previewImage',//预览图片接口
            'uploadImage'//上传图片
        ];
        $uri = urldecode($params['uri']);

        $wechat = \addons\suisunwechat\library\Wechat::instance();
        $res = $wechat->getApp()->jssdk->setUrl($uri)->buildConfig($apis, $debug = false, $beta = false, $json = false);
        $this->success('sdk', $res);
    }

    /**
     * 微信API对接接口
     */
    public function index()
    {
        $app = \addons\suisunwechat\library\Wechat::instance()->getApp();
        $app->server->push(function ($message) use ($app) {
            Log::write($message);
            switch ($message['MsgType']) {
                case 'event':
                    $event = $message['Event'];
                    $eventkey = $message['EventKey'] ? $message['EventKey'] : $message['Event'];
                    switch ($event) {
                        case 'subscribe'://添加关注
                            $reply = WechatConfig::getAttentionReply();
                            if (!empty($reply['key'])) {
                                $eventkey = $reply['key'];
                            }
                            break;
                        case 'unsubscribe'://取消关注
                            //更新用户为取消关注
                            return '';
                        case 'VIEW': //跳转链接,eventkey为链接
                            return '';
                    }
                    $source = Source::where(["eventkey" => $eventkey, 'status' => 1])->find(); //资源响应
                    if ($source) {
                        $response = SourceResponse::instance($source['type']);
                        if ($response) {
                            return $response->response($source['content']);
                        }
                    }
                    break;
                case 'text':
                case 'voice':
                case 'image':
                case 'video':
                case 'location':
                case 'link':
                case 'file':
                default:
                    //消息处理
                    if ($message['MsgType'] == 'text' || $message['MsgType'] == 'voice'){
                        $this->processMsg($message,$app);
                    }
                    $response = MessageResponse::instance($message['MsgType']); //资源响应
                    return $response->response($message['Content']);//发送的内容

            }
        });
        $response = $app->server->serve();
        Log::write($response);
        // 将响应输出
        $response->send();
    }

    /**
     * 登录回调
     */
    public function callback()
    {

    }

    private function processMsg($message, $wechat)
    {
        $openid = $message['FromUserName'];
        $content = $message['MsgType'] == 'text' ? $message['Content'] : '的语音消息：' . $message['Recognition'];
        $adminM = new ServiceAdmin();
        $admin = $adminM->where(['openid' => $openid])->find();

        $this->serviceExpire($wechat);//检测超时客服

        //管理员
        if (!empty($admin)) {
            if ($admin->currentid != '' && $content != '退' && $content != '进') {
                //直接转发
                $message = new Text($content);
                $wechat->customer_service->message($message)->to($admin->currentid)->send();
                $msgs = ServiceLog::where(['uopenid' => $admin->currentid, 'status' => 0])->select();
                //更新所有待回复信息
                foreach ($msgs as $msg) {
                    $msg->status = 1;
                    $msg->reply = $content;
                    $msg->aopenid = $openid;
                    $msg->isUpdate(true)->save();
                }
                return '';
            }
            if ($content == '进') {
                $curClient = $this->getCurClient();
                Db::startTrans();
                try {
                    $msg = ServiceLog::where(['status' => 0, 'aopenid' => '', 'uopenid' => ['NOT IN', $curClient]])->order('createtime', 'asc')->lock(true)->find();
                    if (!empty($msg)) {
                        $admin->status = 1;
                        $admin->currentid = $msg->uopenid;
                        $admin->isUpdate(true)->save();
                        $message = new Text('您已成功加入本次聊天');
                        $wechat->customer_service->message($message)->to($openid)->send();

                        $msgs = ServiceLog::where(['status' => 0, 'uopenid' => $msg->uopenid])->select();
                        foreach ($msgs as $item) {
                            $message = new Text($item->content);
                            $wechat->customer_service->message($message)->to($openid)->send();
                            $item->aopenid = $openid;
                            $item->isUpdate(true)->save();
                        }
                    } else {
                        $message = new Text('当前暂无需要服务的客户');
                        $wechat->customer_service->message($message)->to($openid)->send();
                    }
                    Db::commit();
                } catch (Exception $e) {
                    Log::error('信息处理失败' . $e->getMessage());
                    Db::rollback();
                }
            } elseif ($content == '退') {
                $msgs = ServiceLog::where(['status' => 0, 'uopenid' => $admin->currentid])->select();
                foreach ($msgs as $msg) {
                    $msg->aopenid = $openid;
                    $msg->status = 1;
                    $msg->isUpdate(true)->save();
                }
                $admin->status = 0;
                $admin->currentid = '';
                $admin->isUpdate(true)->save();
                //查找未回复消息
                $curClient = $this->getCurClient();
                Db::startTrans();
                try {
                    $msg = ServiceLog::where(['status' => 0, 'aopenid' => '', 'uopenid' => ['NOT IN', $curClient]])->order('createtime', 'asc')->lock(true)->find();
                    if (!empty($msg)) {
                        $admin->status = 1;
                        $admin->currentid = $msg->uopenid;
                        $admin->isUpdate(true)->save();
                        $msgs = ServiceLog::where(['status' => 0, 'uopenid' => $msg->uopenid])->select();
                        foreach ($msgs as $item) {
                            $message = new Text($item->content);
                            $wechat->customer_service->message($message)->to($openid)->send();
                            $item->aopenid = $openid;
                            $item->isUpdate(true)->save();
                        }
                    } else {
                        $message = new Text('退出成功');
                        $wechat->customer_service->message($message)->to($openid)->send();
                    }

                    Db::commit();
                } catch (Exception $e) {
                    Log::error('信息处理失败' . $e->getMessage());
                    Db::rollback();
                }
            }
            return '';
        }

        //用户发送消息
        $wxuser = \app\admin\model\suisunwechat\WechatFans::get(['openid' => $openid]);
        $username = '新用户';
        if (!empty($wxuser)) {
            $username = $wxuser->nickname;
        }
        //是否已有客服
        $msg = $adminM->where(['currentid' => $openid])->find();
        if (!empty($msg)) {
            //直接转发
            $newdata = [
                'content' => $username . ':' . $content,
                'uopenid' => $openid,
            ];
            ServiceLog::create($newdata);
            $message = new Text($username . ':' . $content);
            $wechat->customer_service->message($message)->to($msg->openid)->send();
            return '';
        } else {
            $config = get_addon_config('suisunwechat');
            $check = ServiceLog::get(['uopenid' => $openid, 'status' => 0]);
            if (empty($check)) {
                //广播所有空闲客服
                $admins = $adminM->where(['status' => 0])->select();
                foreach ($admins as $item) {
                    $sendMsg = [
                        'touser' => $item->openid,
                        'template_id' => $config['service_template_id'],
                        'url' => '',//跳转链接
                        'data' => [
                            'first' => '收到一条客户信息',//标题
                            'keyword1' => '关键词1',
                            'remark' => '回复"进"进入客服系统'//备注
                        ],
                    ];
                    if ($config['service_template_id'] != ''){
                        $res = $wechat->template_message->send($sendMsg);
                        Log::info('$res'.json_encode($res));
                    }
                }
            }
            $msg = [
                'content' => $username . ':' . $content,
                'uopenid' => $openid
            ];
            ServiceLog::create($msg);
        }
    }

    private function serviceExpire($wechat)
    {
        $logM = new ServiceLog();
        $admins = ServiceAdmin::all(['status' => 1]);
        $message = new Text('空闲已超过10分钟，上次对话将自动退出。');
        foreach ($admins as $item) {
            $check = $logM->where(['uopenid' => $item->currentid])->order('createtime', 'desc')->find();
            if ($check->createtime < time() - 600) {
                $wechat->customer_service->message($message)->to($item->openid)->send();
                $item->currentid = '';
                $item->status = 0;
                $item->isUpdate(true)->save();
            }
        }
    }

    private function getCurClient()
    {
        $clients = [];

        $data = ServiceAdmin::all(['currentid' => ['neq', '']]);

        foreach ($data as $item) {
            $clients[] = $item->currentid;
        }

        if (count($clients) == 0) {
            $clients[] = -1;
        }

        return $clients;
    }
}