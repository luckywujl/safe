<?php

namespace app\admin\controller\suisunwechat;

use addons\suisunwechat\library\wechat\broadcast\Broadcast;
use app\common\controller\Backend;
use think\Db;
use think\Exception;
use think\exception\PDOException;
use think\exception\ValidateException;
use think\Log;

/**
 * 消息群发
 *
 * @icon fa fa-circle-o
 */
class WechatBroadcasting extends Backend
{
    
    /**
     * WechatBroadcasting模型对象
     * @var \app\admin\model\suisunwechat\WechatBroadcasting
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\suisunwechat\WechatBroadcasting;
        $this->view->assign("typeList", $this->model->getTypeList());
        $this->view->assign("statusList", $this->model->getStatusList());
    }
    
    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */


    /**
     * 添加
     */
    public function add()
    {
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if ($params) {
                $params = $this->preExcludeFields($params);

                if ($this->dataLimit && $this->dataLimitFieldAutoFill) {
                    $params[$this->dataLimitField] = $this->auth->id;
                }
                $result = false;
                Db::startTrans();
                try {
                    //是否采用模型验证
                    if ($this->modelValidate) {
                        $name = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.add' : $name) : $this->modelValidate;
                        $this->model->validateFailException(true)->validate($validate);
                    }
                    $res = $this->sendMsg($params);
                    $params['msgid'] = $res['msg_id'];
                    $result = $this->model->allowField(true)->save($params);
                    Db::commit();
                } catch (ValidateException $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                } catch (PDOException $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                } catch (Exception $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                }
                if ($result !== false) {
                    $this->success();
                } else {
                    $this->error(__('No rows were inserted'));
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        return $this->view->fetch();
    }

    private function sendMsg(&$params){
        $broadcast = Broadcast::instance($params['type']);
        $params['content'] = $params[$params['type']];
        if ($params['content'] == ''){
            $this->error('请输入或选择正确的内容');
        }
        switch ($params['target']){
            case 'all':
                $res = $broadcast->all($params['content']);
                break;
            case 'user':
                $openids = explode(',',$params['openids']);
                if (count($openids) <= 1){
                    $this->error('群发至少两个用户');
                }
                $res = $broadcast->user($params['content'],$openids);
                break;
            case 'tag':
                if ($params['tags'] == ''){
                    $this->error('请选择标签组');
                }
                $res = $broadcast->tag($params['content'],$params['tags']);
                break;
        }
        Log::info('$res'.json_encode($res));
        if ($res['errcode'] > 0){
            $this->error($res['errmsg']);
        }
        return $res;
    }

    public function preview(){
//        id: $("#c-preview").val(),
//                    type: $("#c-type").val(),
//                    content: $("#c-content").val(),
//                    imagetext: $("#c-imagetext").val(),
//                    image: $("#c-image").val(),
//                    video: $("#c-video").val(),
//                    audio: $("#c-audio").val(),
//                    article: $("#c-article").val()
        $id = $this->request->post('id');
        $type = $this->request->post('type');
        $content = $this->request->post('content');
        $imagetext = $this->request->post('imagetext');
        $image = $this->request->post('image');
        $video = $this->request->post('video');
        $audio = $this->request->post('audio');
        $article = $this->request->post('article');
        $broadcast = Broadcast::instance($type);
        switch ($type){
            case 'text':
                $res = $broadcast->preview($content,$id);
                break;
            case 'textandphoto':
                $res = $broadcast->preview($imagetext,$id);
                break;
            case 'image':
                $res =  $broadcast->preview($image,$id);
                break;
            case 'video':
                $res = $broadcast->preview($video,$id);
                break;
            case 'voice':
                $res =  $broadcast->preview($audio,$id);
                break;
            case 'article':
                $res = $broadcast->preview($article,$id);
                break;
        }
        if ($res['errcode'] > 0){
            $this->error($res['errmsg']);
        }
        $this->success();
    }
}
