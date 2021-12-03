<?php

namespace app\admin\controller\suisunwechat\material;

use app\common\controller\Backend;
use addons\suisunwechat\utils\JsonUtil;

/**
 * 图文消息
 *
 * @icon fa fa-circle-o
 */
class WechatImageText extends Backend
{
    
    /**
     * WechatImageText模型对象
     * @var \app\admin\model\suisunwechat\material\WechatImageText
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\suisunwechat\material\WechatImageText;
        $this->assignconfig('cdnurl',cdnurl('',true));

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
            $params = $this->request->post("list");
            if ($params) {
                $list = json_decode($params,true);
                if(count($list)>8){
                    $this->error(__('文章篇数不能大于8', ''));
                }
                if(count($list)<=0){
                    $this->error(__('文章篇数不能大于0', ''));
                }
                $mandata = $list[0];
                $this->model->title = $mandata['title'];
                $this->model->desc = $mandata['description'];
                $this->model->image = $mandata['image'];
                $this->model->url = $mandata['url'];
                $this->model->items = json_encode($list,JSON_UNESCAPED_UNICODE);
                if($this->model->save()){
                    $this->success("保存成功");
                }else{
                    $this->success("保存失败");
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        return $this->view->fetch();
    }

    /**
     * 编辑
     */
    public function edit($ids = null)
    {
        $row = $this->model->get($ids);
        if (!$row) {
            $this->error(__('No Results were found'));
        }
        if ($this->request->isPost()) {
            $params = $this->request->post("list");
            if ($params) {
                $list = JsonUtil::decode($params);
                if(count($list)>8){
                    $this->error(__('文章篇数不能大于8', ''));
                }
                if(count($list)<=0){
                    $this->error(__('文章篇数不能大于0', ''));
                }
                $mandata = $list[0];
                $row->title = $mandata['title'];
                $row->desc = $mandata['description'];
                $row->image = $mandata['image'];
                $row->url = $mandata['url'];
                $row->items = JsonUtil::encode($list);
                if($row->save()){
                    $this->success("保存成功");
                }else{
                    $this->success("保存失败");
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        $this->view->assign("row", $row);
        $this->assignconfig("info", json_decode($row['items']));
        return $this->view->fetch();
    }
    

}
