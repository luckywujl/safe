<?php

namespace app\admin\controller\training;

use app\common\controller\Backend;
use fast\Tree;
use Think\Db;

/**
 * 培训管理
 *
 * @icon fa fa-circle-o
 */
class Main extends Backend
{
    
    /**
     * Main模型对象
     * @var \app\admin\model\training\Main
     */
    protected $model = null;
    protected $dataLimit = 'personal';
	 protected $dataLimitField = 'company_id';
	 protected $noNeedRight = ['selectuser','jstree','deluser'];
    
    

    public function _initialize()
    { 
        parent::_initialize();
        $config = get_addon_config('training');
        if($config['datalimit'] !== "false"){
            $this->dataLimit = $config['datalimit'];
        }
        $this->model = new \app\admin\model\training\Main;
        $this->view->assign("statusList", $this->model->getStatusList());
        $tree = Tree::instance();
        $category_model = new \app\admin\model\training\Category;
        $tree->init(collection($category_model->order('weigh desc,id desc')->where('type', 'main')->select())->toArray(), 'pid');
        $this->categorylist = $tree->getTreeList($tree->getTreeArray(0), 'name');
        $categorydata = [];
        foreach ($this->categorylist as $k => $v) {
            $categorydata[$v['id']] = $v;
        }
        $this->view->assign("parentList", $categorydata);
        //以下是选择学员的数据
        $user = model('User');
        $department = new \app\admin\model\user\Department;
        $usertree = Tree::instance();
        $usertree->init(collection($department->where(['company_id'=>$this->auth->company_id])->order('weigh desc,id desc')->select())->toArray(), 'pid');
        $this->departmentlist = $usertree->getTreeList($usertree->getTreeArray(0), 'name');
        $departmentdata = [0 => ['type' => 'all', 'name' => __('None')]];
        foreach ($this->departmentlist as $k => $v) {
            $departmentdata[$v['id']] = $v;
        }
        //$this->view->assign("flagList", $this->model->getFlagList());
        //$this->view->assign("typeList", $this->typelist);
        $this->view->assign("departmentList", $departmentdata);
        $this->assignconfig("departmentList", $departmentdata);
        //$this->assignconfig("typeList", $this->typelist);
        //$this->assignconfig('typeList', $this->typelist);
    }

    public function import()
    {
        parent::import();
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
                    $params[$this->dataLimitField] = $this->auth->company_id;
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
        $category = $this->request->request("category", '');
        $this->view->assign("category", $category);
        return $this->view->fetch();
    }

    
    /**
     * 查看
     */
    public function selectuser()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags', 'trim']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $list = $user
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
}
