<?php

namespace app\admin\controller\suisunwechat;

use addons\suisunwechat\utils\JsonUtil;
use app\admin\model\suisunwechat\Lang;
use app\common\controller\Backend;
use think\Db;
use think\Exception;
use think\exception\PDOException;
use think\exception\ValidateException;


/**
 * 微信管理
 *
 * @icon fa fa-circle-o
 */
class Wechat extends Backend
{

    /**
     * Wechat模型对象
     * @var \app\admin\model\suisunwechat\Wechat
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\suisunwechat\Wechat;
        $this->view->assign("typeList", $this->model->getTypeList());
        $this->view->assign("langList", Lang::all());
        $this->view->assign("tagList", \app\admin\model\suisunwechat\WechatFansTag::all());
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
        //当前是否为关联查询
        $this->relationSearch = false;
        //设置过滤方法
        $this->request->filter(['strip_tags', 'trim']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model
                ->where($where)
                ->order($sort, $order)
                ->count();

            $list = $this->model
                ->where($where)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();

            foreach ($list as $row) {
                $row->visible(['id', 'type', 'name', 'content', 'createtime', 'updatetime', 'status']);
            }
            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }


    /**
     * 添加
     */
    public function add()
    {
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            $matchrule = $this->request->post("matchrule/a");
            if ($params) {
                $params = $this->preExcludeFields($params);

                if ($this->dataLimit && $this->dataLimitFieldAutoFill) {
                    $params[$this->dataLimitField] = $this->auth->id;
                }
                $params['matchrule'] = JsonUtil::encode($matchrule);
                $result = false;
                Db::startTrans();
                try {
                    //是否采用模型验证
                    if ($this->modelValidate) {
                        $name = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.add' : $name) : $this->modelValidate;
                        $this->model->validateFailException(true)->validate($validate);
                    }
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

    /**
     * 编辑
     */
    public function edit($ids = null)
    {
        $row = $this->model->get($ids);
        if (!$row) {
            $this->error(__('No Results were found'));
        }
        $adminIds = $this->getDataLimitAdminIds();
        if (is_array($adminIds)) {
            if (!in_array($row[$this->dataLimitField], $adminIds)) {
                $this->error(__('You have no permission'));
            }
        }
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            $matchrule = $this->request->post("matchrule/a");
            if ($params) {
                $params = $this->preExcludeFields($params);
                $params['matchrule'] = JsonUtil::encode($matchrule);
                $result = false;
                Db::startTrans();
                try {
                    //是否采用模型验证
                    if ($this->modelValidate) {
                        $name = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.edit' : $name) : $this->modelValidate;
                        $row->validateFailException(true)->validate($validate);
                    }
                    $result = $row->allowField(true)->save($params);
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
                    $this->error(__('No rows were updated'));
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        $this->view->assign("row", $row);
        return $this->view->fetch();
    }



    /**
     * 菜单编辑
     * @return string
     */
    public function menu($id = 0)
    {
        $row = $this->model->get($id);
        if (!$row) {
            $this->error(__('No Results were found'));
        }
        if ($this->request->isPost()) { //提交数据
            $rule = $this->request->post('rule/a');
            $type = $this->request->post('type');
            if (!in_array($type, ['save', 'send'])) {
                $this->success(__("method param miss"));
            }
            $rule = $this->filterRule($rule);
            if (empty($rule)) {
                $this->error(__("参数错误"));
            }

            //保存数据
            $row->content = JsonUtil::encode($rule);
            if ($type == 'send') { //发布菜单
                $app = \addons\suisunwechat\library\Wechat::instance()->getApp();
                if($row->type==\app\admin\model\suisunwechat\Wechat::TYPE_CONDITIONAL){
                    $matchrule = $row->matchrule;
                    if(empty($matchrule)){
                        $this->error("请先设置个性化信息");
                    }
                    if(!empty($row->menuid)){
                        //删除旧的菜单
                        $delres = $app->menu->delete($row->menuid);
                        if(isset($delres['errcode']) && $delres['errcode']!=0){
                            $this->error("发布失败,请稍后再试");
                        }
                    }
                    $res = $app->menu->create($rule,$matchrule);
                    if(isset($res['menuid'])){
                        $row->menuid = $res['menuid'];
                    }
                }else{
                    $res = $app->menu->create($rule);
                }
                if($row->type==\app\admin\model\suisunwechat\Wechat::TYPE_BASE){ //基本菜单只有一个
                    $this->model->save(['status'=>0],['status'=>1,'type'=>\app\admin\model\suisunwechat\Wechat::TYPE_BASE]);
                }
                if ((isset($res['errcode']) && $res['errcode'] == 0) || isset($res['menuid'])) {
                    $row->status = 1;
                } else {
                    $this->error($res['errmsg']);
                }
            }
            $row->save();
            $this->success();
        }
        $rule = $this->recoveryRule(JsonUtil::decode($row->content),0);//回复数据字段
        $this->assignconfig('button', JsonUtil::encode($rule));
        return $this->view->fetch();
    }


    /**
     * 删除
     */
    public function del($ids = "")
    {
        $app = \addons\suisunwechat\library\Wechat::instance()->getApp();
        if ($ids) {
            $pk = $this->model->getPk();
            $adminIds = $this->getDataLimitAdminIds();
            if (is_array($adminIds)) {
                $this->model->where($this->dataLimitField, 'in', $adminIds);
            }
            $list = $this->model->where($pk, 'in', $ids)->select();

            $count = 0;
            Db::startTrans();
            try {
                foreach ($list as $k => $v) {
                    if($v->type==\app\admin\model\suisunwechat\Wechat::TYPE_CONDITIONAL && !empty($v->menuid)){
                        $delres = $app->menu->delete($v->menuid);
                        if(isset($delres['errcode']) && $delres['errcode']==0){
                            $count += $v->delete();
                        }
                    }else{
                        $count += $v->delete();
                    }
                }
                Db::commit();
            } catch (PDOException $e) {
                Db::rollback();
                $this->error($e->getMessage());
            } catch (Exception $e) {
                Db::rollback();
                $this->error($e->getMessage());
            }
            if ($count) {
                $this->success();
            } else {
                $this->error(__('No rows were deleted'));
            }
        }
        $this->error(__('Parameter %s can not be empty', 'ids'));
    }



    /**\
     * 拉取微信后台菜单
     */
    public function getmenu(){
        $app = \addons\suisunwechat\library\Wechat::instance()->getApp();
        try {
            $list = $app->menu->list();
            if(!is_array($list)){
                $this->error("同步菜单失败,请检查公众号参数是否正确");
            }
            //删除所有存在的菜单(已发布的)
            \app\admin\model\suisunwechat\Wechat::destroy(['status'=>1]);
            if(isset($list['menu'])){
                $this->addmenu($list['menu']['button'],'','',\app\admin\model\suisunwechat\Wechat::TYPE_BASE);
            }
            if(isset($list['conditionalmenu'])){
                foreach ($list['conditionalmenu'] as $item){
                    $this->addmenu($item['button'],$item['menuid'],JsonUtil::encode($item['matchrule']),\app\admin\model\suisunwechat\Wechat::TYPE_CONDITIONAL);
                }
            }
        } catch (\Exception $e) {  //如书写为（Exception $e）将无效
            if (strpos($e->getMessage(), 'ip')) {
                $this->error('请将当前IP地址加入公众号后台IP白名单');
            }else{
                $this->error("同步菜单失败,请检查公众号参数是否正确");
            }
        }
        $this->success("同步成功");
    }

    protected function addmenu($data,$menuid,$matchrule,$type){
        $model = new \app\admin\model\suisunwechat\Wechat();
        $data = [
            'status'=>1,
            'menuid'=>$menuid,
            'matchrule'=>$matchrule,
            'content'=>JsonUtil::encode($data),
            'type'=>$type,
            'name'=>$type=='base'?'微信公众号菜单':'个性化菜单'
        ];
        $model->save($data);
    }


    /**
     * 菜单参数过滤
     */
    protected function filterRule($rule = [])
    {
        if (empty($rule)) {
            return $rule;
        }
        foreach ($rule as $key => $item) {
            if (!isset($item['type'])) {
                unset($rule[$key]);
                break;
            }
            if (isset($item['sub_button'])) {
                $subrule = $this->filterRule($item['sub_button']);
                if (!empty($subrule)) {
                    $item['sub_button'] = $subrule;
                } else {
                    unset($item['sub_button']);//删除子菜单
                }
            }
            $param = ['type' => '', 'name' => '', 'key' => ''];
            switch ($item['type']) {
                case 'miniprogram': //小程序
                    $param = ['type' => '', 'name' => '', 'url' => '', 'appid' => '', 'pagepath' => ''];
                    break;
                case 'view': //小程序
                    $param = ['type' => '', 'name' => '', 'url' => ''];
                    break;
            }
            if (isset($item['sub_button']) && !empty($item['sub_button'])) {
                $param = ['name' => ''];
                $param = array_merge($param, ['sub_button' => '']);
            }
            $rule[$key] = array_intersect_key($item, $param);
        }
        return $rule;
    }

    /**
     * 菜单参数过滤
     */
    protected function recoveryRule($rule = [],$index = 0)
    {
        if (empty($rule)) {
            return [];
        }

        foreach ($rule as $key => $item) {
            if (isset($item['sub_button'])) {
                $subrule = $this->recoveryRule($item['sub_button'],1);
                if (!empty($subrule)) {
                    $item['sub_button'] = $subrule;
                }
            }
            $param = ['type' => '', 'name' => '', 'url' => '', 'appid' => '', 'pagepath' => '', 'key'=>''];
            if($index==0){
                $param['sub_button'] = [];
            }
            $param = array_merge($param,$item);
            $rule[$key] = $param;
        }
        return $rule;
    }






}
