<?php

namespace addons\suisunwechat\library;

use EasyWeChat\Factory;
use traits\think\Instance;

/**
 *
 */
class Wechat
{
    protected $config;
    protected $app;


    use Instance;


    public function __construct()
    {
        $this->config = get_addon_config('suisunwechat');
        $this->app    = Factory::officialAccount($this->config);
    }

    // 返回实例
    public function getApp() {
        return $this->app;
    }
}
