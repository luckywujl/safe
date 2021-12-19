<?php

namespace addons\qrcode\controller;

use think\addons\Controller;
use think\Response;


/**
 * 二维码生成
 *
 */
class Index extends Controller
{

     public function _initialize()
    {
        parent::_initialize();
        $auth = $this->auth;
     }
    public function index()
    {
        return $this->view->fetch();
    }

    // 生成二维码
    public function build()
    {
        $config = get_addon_config('qrcode');
        if ($this->request->isPost()) {
            $params = $this->request->post();
            $params = array_intersect_key($params, array_flip(['text', 'size', 'padding', 'errorlevel', 'foreground', 'background', 'logo', 'logosize', 'logopath', 'label', 'labelfontsize', 'labelalignment','company','code']));
            $params['text'] = $this->request->post('text', $config['text'], 'trim');
        		$params['label'] = $this->request->post('label', $config['label'], 'trim');
        		$params['company'] = $this->request->post('company', '0', 'trim');
        		$params['code'] = $this->request->post('code', '0', 'trim');
        		$params['write'] = $this->request->post('write','0','trim');
            $qrCode = \addons\qrcode\library\Service::qrcode($params);

        		$mimetype = $config['format'] == 'png' ? 'image/png' : 'image/svg+xml';

        		$response = Response::create()->header("Content-Type", $mimetype);

        		// 直接显示二维码
        		header('Content-Type: ' . $qrCode->getContentType());
        		$response->content($qrCode->writeString());

        		// 写入到文件	
        	 	if ($params['write']=='1') {  //是否字文件由传过来的数据决定
            	$qrcodePath = ROOT_PATH . 'public/uploads/qrcode/'.$params['company'].'/';
            	if (!is_dir($qrcodePath)) {
               	 @mkdir($qrcodePath);
            	}
            	if (is_really_writable($qrcodePath)) {
                	$filePath = $qrcodePath . $params['code'] . '.' . $config['format'];
                	$qrCode->writeFile($filePath);
                	
            	}
        		}
        		$this->success('二维码生成！');
        }
        $params = $this->request->get();
        $params = array_intersect_key($params, array_flip(['text', 'size', 'padding', 'errorlevel', 'foreground', 'background', 'logo', 'logosize', 'logopath', 'label', 'labelfontsize', 'labelalignment','company','code']));
        $params['text'] = $this->request->get('text', $config['text'], 'trim');
        $params['label'] = $this->request->get('label', $config['label'], 'trim');
        $params['company'] = $this->request->get('company', '0', 'trim');
        $params['code'] = $this->request->get('code', '0', 'trim');
        $params['write'] = $this->request->get('write','0','trim');

        $qrCode = \addons\qrcode\library\Service::qrcode($params);

        $mimetype = $config['format'] == 'png' ? 'image/png' : 'image/svg+xml';

        $response = Response::create()->header("Content-Type", $mimetype);

        // 直接显示二维码
        header('Content-Type: ' . $qrCode->getContentType());
        $response->content($qrCode->writeString());

        // 写入到文件

        	 if ($params['write']=='1') {  //是否字文件由传过来的数据决定
            $qrcodePath = ROOT_PATH . 'public/uploads/qrcode/'.$params['company'].'/';
            if (!is_dir($qrcodePath)) {
                @mkdir($qrcodePath);
            }
            if (is_really_writable($qrcodePath)) {
                //$filePath = $qrcodePath . md5('',$params) . '.' . $config['format'];
                $filePath = $qrcodePath . $params['code'] . '.' . $config['format'];
                $qrCode->writeFile($filePath);
            }
        }

        return $response;
       
    }
    
    // 生成二维码
    public function buildpoint()
    {
        $config = get_addon_config('qrcode');
        //$params = $this->request->get();
        $params = $this->request->post();
        $params = array_intersect_key($params, array_flip(['text', 'size', 'padding', 'errorlevel', 'foreground', 'background', 'logo', 'logosize', 'logopath', 'label', 'labelfontsize', 'labelalignment']));

       // $params['text'] = $this->request->get('text', $config['text'], 'trim');
       // $params['label'] = $this->request->get('label', $config['label'], 'trim');
       // $params['company_id'] = $this->request->get('company_id', '0', 'trim');
       // $params['code'] = $this->request->get('code', '0', 'trim');


        $qrCode = \addons\qrcode\library\Service::qrcode($params);

        $mimetype = $config['format'] == 'png' ? 'image/png' : 'image/svg+xml';

        $response = Response::create()->header("Content-Type", $mimetype);

        // 直接显示二维码
        header('Content-Type: ' . $qrCode->getContentType());
        $response->content($qrCode->writeString());

        // 写入到文件
        
         $qrcodePath = ROOT_PATH . 'public/uploads/qrcode/'.$params['company'].'/';
            if (!is_dir($qrcodePath)) {
                @mkdir($qrcodePath);
            }
            if (is_really_writable($qrcodePath)) {
                $filePath = $qrcodePath . $params['code'] . '.' . $config['format'];
                $qrCode->writeFile($filePath);
            }
       

        return $response;
    }
    
  


}
