<?php

namespace addons\suisunwechat\library\wechat\server;

use EasyWeChat\Factory;
use fast\Http;

/**
 *
 */
class WxOfficialAccount
{
    protected $config;
    protected $app;


    public function __construct($config)
    {
        $this->config = $config;
        $this->app    = Factory::officialAccount($this->config);//微信公众号应用
    }

    // 返回实例
    public function getApp() {
        return $this->app;
    }

    //小程序:获取openid&session_key
    public function code($code)
    {
        return $this->app->auth->session($code);
    }

    public function oauth()
    {
        $oauth = $this->app->oauth;
        return $oauth;
    }

    //解密信息
    public function decryptData($session, $iv, $encryptData)
    {
        $data = $this->app->encryptor->decryptData($session, $iv, $encryptData);

        return $data;
    }


    public function unify($orderBody)
    {
        $result = $this->app->order->unify($orderBody);
        return $result;
    }

    public function bridgeConfig($prepayId)
    {
        $jssdk = $this->app->jssdk;
        $config = $jssdk->bridgeConfig($prepayId, false);
        return $config;
    }

    public function notify()
    {
        $result = $this->app;
        return $result;
    }

    //获取accessToken
    public function getAccessToken()
    {
        $accessToken = $this->app->access_token;
        $token = $accessToken->getToken(); // token 数组  token['access_token'] 字符串
        //$token = $accessToken->getToken(true); // 强制重新从微信服务器获取 token.
        return $token;
    }

    public function sendTemplateMessage($attributes)
    {
        extract($attributes);
        $this->app->template_message->send([
            'touser' => $openId,
            'template_id' => $templateId,
            'page' => $page,
            'form_id' => $formId,
            'data' => $data,
            'emphasis_keyword' => $emphasis_keyword
        ]);
    }
}
