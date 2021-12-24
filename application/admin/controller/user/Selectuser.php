<?php

namespace app\admin\controller\user;

use app\common\controller\Backend;
use fast\Tree;
use Think\Db;

/**
 * 培训管理
 *
 * @icon fa fa-circle-o
 */
class Selectuser extends Backend
{
    
    /**
     * Main模型对象
     * @var \app\admin\model\training\Main
     */
    protected $model = null;
    protected $dataLimit = 'personal';
	 protected $dataLimitField = 'company_id';
	 protected $noNeedRight = ['index','jstree','deluser','selectadmin'];
    
    

    public function _initialize()
    { 
        parent::_initialize();
        
        $this->model = new \app\admin\model\user\Selectuser;
        //以下是选择学员的数据
        
        $department = new \app\admin\model\user\Department;
        $departmenttree = Tree::instance();
        $departmenttree->init(collection($department->where(['company_id'=>$this->auth->company_id])->order('weigh desc,id desc')->select())->toArray(), 'pid');
        $this->departmentlist = $departmenttree->getTreeList($departmenttree->getTreeArray(0), 'name');
        $departmentdata = [0 => ['type' => 'all', 'name' => __('None')]];
        foreach ($this->departmentlist as $k => $v) {
            $departmentdata[$v['id']] = $v;
        }
        
        $this->view->assign("departmentList", $departmentdata);
        $this->assignconfig("departmentList", $departmentdata);
       
    }

   

    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */

    /**
     * 选择用户
     */
    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags', 'trim']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $list = $this->model
                ->with('group,department')
                ->where($where)
                ->where(['user.company_id'=>$this->auth->company_id])
                ->order($sort, $order)
                ->paginate($limit);
            foreach ($list as $k => $v) {
                $v->avatar = $v->avatar ? cdnurl($v->avatar, true) : letter_avatar($v->nickname);
                $v->hidden(['password', 'salt']);
            }
            $result = array("total" => $list->total(), "rows" => $list->items());

            return json($result);
        }
        $user_ids = $this->request->request("ids", '');
        $this->view->assign("user_ids", $user_ids);
        return $this->view->fetch();
    }
     /*
     * 移除
     */
    public function deluser()
    {
    	$user_ids = $this->request->request("ids", '');
    	if($user_ids) {
    		$this->success($user_ids,null,$user_ids);
    		//return json($user_ids);
    	}
    
    }
    /**
     * 选择用户
     */
    public function selectadmin()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags', 'trim']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $list = $this->model
                ->with('group,department')
                ->where($where)
                ->where(['user.company_id'=>$this->auth->company_id])
                ->order($sort, $order)
                ->paginate($limit);
            foreach ($list as $k => $v) {
                $v->avatar = $v->avatar ? cdnurl($v->avatar, true) : letter_avatar($v->nickname);
                $v->hidden(['password', 'salt']);
            }
            $result = array("total" => $list->total(), "rows" => $list->items());

            return json($result);
        }
        
        return $this->view->fetch();
    }
}
