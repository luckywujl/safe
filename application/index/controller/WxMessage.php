<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/29
 * Time: 10:26
 */

namespace app\index\controller;


use think\Controller;
use think\Db;

class WxMessage extends Controller
{
    private $appId;
    private $appSecret;

    public function __construct($appId, $appSecret) {
        $this->appId = $appId;
        $this->appSecret = $appSecret;
    }
   //   发送消息方法
    public function sendMsg($tem_id,$data,$openid,$return_url='')
    {
        if($tem_id == ''){
            $tem_id = "2enJepBbIbwFssJlT1bjkHLcmrFzw2YWddMyWC1RzCQ";
        }
//        $user_id = session('user.id');
//        $user_info = Db::name('user')->find($user_id);
//        $openid =$user_info['wx_openid'];
        // $appid = config('wx.appid');
        // $appsecret = config('wx.appsecret');
        $appid = 'wx4f79233878b9f770';
        $appsecret = 'c483da45c62e62784c929aa6722c6de9';
//        $data = [
//            'first'=>['value'=>'您有新的隐患整改通知！','color'=>"#000"],
//            'keyword1'=>['value'=>'吴俊雷','color'=>'#F70997'],
//            'keyword2'=>['value'=>date("Y-m-d H:i:s"),'color'=>'#248d24'],
//            'keyword3'=>['value'=>'安全检查','color'=>'#000'],
//            'keyword4'=>['value'=>'井盖丢失','color'=>'#000'],
//            'keyword5'=>['value'=>date("Y-m-d H:i:s"),'color'=>'#000'],
//            'remark'  =>['value'=>'请尽快处理','color'=>'#1784e8']
//        ];
        //$return_url = 'http://www.baidu.com';      //  消息详情页面
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$appsecret;
        if (cookie('access_token')){
            $access_token2 =cookie('access_token');
        }else{
            $json_token=$this->curl_post($url);
            $access_token1=json_decode($json_token,true);
           // var_dump($access_token1);
            $access_token2 = $access_token1['access_token'];
            setcookie('access_token',$access_token2,7200);
        }//缓存assesstoken
        //$access_token2 = $this->getAcessToken($appid,$appsecret);
        $params1 = json_encode($this->json_tempalte($openid,$return_url,$data,$tem_id),JSON_UNESCAPED_UNICODE);
        $url="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$access_token2;
        $params = $this->curl_post($url,urldecode($params1));
        //dump($params);exit();
        $params = json_decode($params,true);
        if ($params['errcode']==0){
            //return '发送成功!';
            exit();
        }else{
            return '发送失败!';
        }
    }

    /**
     * @param $openid 用户的openid
     * @param string $url  点击消息详情的链接
     * @param array $data     消息配置  具体根据每个模板的要求进行设置
     * @param string $template_id     模板id
     * @return array
     */
    function json_tempalte($openid,$url='',$data,$template_id = '2enJepBbIbwFssJlT1bjkHLcmrFzw2YWddMyWC1RzCQ'){
        $template=[
            'touser'=>$openid,//openID
            'template_id'=>$template_id,//模版id
            'url'=>$url,
            'topcolor'=>"#7B68EE",
            'data'=>$data
        ];//各个参数不明白的就去看文档，很详细。
        return $template;
    }

    /**
     *  curl请求
     * @param $url  请求的目的地址
     * @param array $data 请求带的数据
     * @return mixed
     *
     */
    function curl_post($url , $data=array()){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        // POST数据
        curl_setopt($ch, CURLOPT_POST, 1);
        // 把post的变量加上
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    public function return_url()
    {
        $data = input();
        \think\Cache::set('data',$data);
//        return 1234;
    }

    function getAcessToken($appid,$secret){
        //获取access_token，并缓存
        $file = RUNTIME_PATH.'/access_token1';//缓存文件名access_token1
        $appid='wx4f79233878b9f770'; // 填写自己的appid
        $secret='c483da45c62e62784c929aa6722c6de9'; // 填写自己的appsecret
        $expires = 3600;//缓存时间1个小时
        if(file_exists($file)) {
            $time = filemtime($file);
            if(time() - $time > $expires) {
                $token = null;
            }else {
                $token = file_get_contents($file);
            }
        }else{
            fopen("$file", "w+");
            $token = null;
        }
        if (!$token || strlen($token) < 6) {
            $res = file_get_contents("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$secret."");
            $res = json_decode($res, true);
            $token = $res['access_token'];
            @file_put_contents($file, $token);
        }
        return $token;
    }
}