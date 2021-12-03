<?php

namespace app\admin\controller\suisunwechat;

use app\common\controller\Backend;
use think\Db;
use think\Log;

/**
 * 粉丝管理
 *
 * @icon fa fa-circle-o
 */
class WechatFans extends Backend
{
    
    /**
     * WechatFans模型对象
     * @var \app\admin\model\suisunwechat\WechatFans
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\suisunwechat\WechatFans;
        $this->view->assign("subscribeList", $this->model->getSubscribeList());
    }
    
    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */

    /**
     * 查看
     */
    public function index()
    {
        $tagid = $this->request->param('tagid','');
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            $tagWhere = [];
            if ($tagid != ''){
                $tagWhere['tagids'] = Db::raw("find_in_set($tagid,tagids)");;
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model
                ->where($where)
                ->where($tagWhere)
                ->order($sort, $order)
                ->count();

            $list = $this->model
                ->where($where)
                ->where($tagWhere)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();

            foreach ($list as $row){
                $tags = $row->tagids;
                if ($tags == '' || is_null($tags)){
                    $tags = -1;
                }
                $tagData = \app\admin\model\suisunwechat\WechatFansTag::all(['tag_id' => ['in',$tags]]);
                $row->tagdata = implode(',',array_column($tagData,'name'));
            }
            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        $this->view->assign('tagid',$tagid);
        $this->assignconfig('tagid',$tagid);
        return $this->view->fetch();
    }


    public function sync(){
        $wechat = new \addons\suisunwechat\library\Wechat('wxOfficialAccount');
        $data = $wechat->getApp()->user->list();

        if ($data['total'] > 0){
            $userlist = $data['data']['openid'];
            $fansM = new \app\admin\model\suisunwechat\WechatFans;
            foreach ($userlist as $user){
                $check = $fansM->where(['openid' => $user])->find();

                if (empty($check)){
                    $fansM::create(['openid' => $user]);
                }
            }
            //用户详细信息

            $count = 0;
            foreach ($userlist as $user){
                $row = $wechat->getApp()->user->get($user);
                $fan = $fansM->where(['openid' => $user])->find();
                $fan->subscribe = $row['subscribe'];
                $fan->nickname = $row['nickname'];
                $fan->sex = $row['sex'];
                $fan->country = $row['country'];
                $fan->province = $row['province'];
                $fan->city = $row['city'];
                $fan->subscribe_time = $row['subscribe_time'];
                $fan->headimgurl = $row['headimgurl'];
                $fan->tagids = implode(',',$row['tagid_list']);
                $fan->isUpdate(true)->save();
                $count++;
                if ($count % 20 == 0){
                    sleep(1);
                }
            }
        }

        $this->success();
    }

    public function changetag(){
        $ids = $this->request->get('ids');

        if ($this->request->isPost()){
            $params = $this->request->post('row/a');

            $users = \app\admin\model\suisunwechat\WechatFans::all(['id' => ['in',$ids]]);
            $openIds = array_column($users,'openid');
            $wechat = new \addons\suisunwechat\library\Wechat('wxOfficialAccount');
            $res = $wechat->getApp()->user_tag->tagUsers($openIds, $params['tag_id']);
            if ($res['errcode'] > 0){
                $this->error($res['errmsg']);
            }
            $this->sync();
            $this->success();
        }

        $taglist = \app\admin\model\suisunwechat\WechatFansTag::all();
        $this->view->assign('tagList',$taglist);
        $this->view->assign('ids',$ids);
        return $this->view->fetch();
    }

    public function removetag(){

        if ($this->request->isPost()){
            $ids = $this->request->post('ids/a');
            $tagid = $this->request->post('tagid');

            $users = \app\admin\model\suisunwechat\WechatFans::all(['id' => ['in',$ids]]);
            $openIds = array_column($users,'openid');
            $wechat = new \addons\suisunwechat\library\Wechat('wxOfficialAccount');
            $res = $wechat->getApp()->user_tag->untagUsers($openIds, $tagid);
            if ($res['errcode'] > 0){
                $this->error($res['errmsg']);
            }
            $this->sync();
            $this->success();
        }
    }
}
