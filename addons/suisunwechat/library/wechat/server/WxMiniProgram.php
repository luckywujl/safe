<?php
/**
 * Created by PhpStorm.
 * User: JIAN
 * Date: 2020/8/24
 * Time: 16:41
 */

namespace addons\suisunwechat\library\wechat\server;


use EasyWeChat\Factory;
use fast\Http;

class WxMiniProgram
{
    protected $config;
    protected $app;


    public function __construct($config)
    {
        $this->config = $config;
        $this->app    = Factory::miniProgram($this->config);//微信小程序
    }

    // 返回实例
    public function getApp() {
        return $this->app;
    }


    // 同步小程序直播
    public function live(Array $params = [])
    {
        $default = [
            'start' => 0,
            'limit' => 10
        ];
        $params = array_merge($default, $params);
        $default = json_encode($params);


        $access_token = $this->app->access_token->getToken();
        $getRoomsListUrl = "https://api.weixin.qq.com/wxa/business/getliveinfo?access_token={$access_token['access_token']}";
        $headers = ['Content-type: application/json'];
        $options = [
            CURLOPT_HTTPHEADER => $headers
        ];
        $result = Http::sendRequest($getRoomsListUrl, $default, 'POST', $options);
        if (isset($result['ret']) && $result['ret']) {
            $msg = json_decode($result['msg'], true);
            $result = $msg;
        }

//        $result = $this->app->live->getRooms(...array_values($params));

        $rooms = [];
        if ($result && $result['errcode'] == 0 && $result['errmsg'] === 'ok') {
            $rooms = $result['room_info'];
        }

        return $rooms;
    }

    // 小程序直播回放
    public function liveReplay(array $params = [])
    {
        $default = [
            'room_id' => 0,
            'start' => 0,
            'limit' => 20
        ];

        $params = array_merge($default, $params);
        $default = json_encode($params);
        $access_token = $this->app->access_token->getToken();
        $getPlayBackListUrl = "http://api.weixin.qq.com/wxa/business/getliveinfo?access_token={$access_token['access_token']}";
        $headers = ['Content-type: application/json'];
        $options = [
            CURLOPT_HTTPHEADER => $headers
        ];
        $result = Http::sendRequest($getPlayBackListUrl, $default, 'POST', $options);
        if (isset($result['ret']) && $result['ret']) {
            $msg = json_decode($result['msg'], true);
            $result = $msg;
        }
//        $result = $this->app->live->getPlaybacks(...array_values($params));

        $liveReplay = [];
        if ($result && $result['errcode'] == 0 && $result['errmsg'] === 'ok') {
            $liveReplay = $result['live_replay'];
        }

        return $liveReplay;
    }

}