<?php

namespace app\admin\controller\user;

use app\common\controller\Backend;
use Think\Db;
use fast\Tree;
/**
 * 部门管理
 *
 * @icon fa fa-circle-o
 */
class Department extends Backend
{
    
    /**
     * Department模型对象
     * @var \app\admin\model\user\Department
     */
    protected $model = null;
    protected $dataLimit = 'personal';
	 protected $dataLimitField = 'company_id';
	 protected $departmentlist = [];
	 protected $noNeedRight = ['selectpage','jstree'];
    protected $typelist = [[
        'key'=>'企业部门',
        'name'=>'企业部门'
    ],[
        'key'=>'施工队',
        'name'=>'施工队'
    ]];

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\user\Department;
        $tree = Tree::instance();
        $tree->init(collection($this->model->where(['company_id'=>$this->auth->company_id])->order('weigh desc,id desc')->select())->toArray(), 'pid');
        $this->departmentlist = $tree->getTreeList($tree->getTreeArray(0), 'name');
        $departmentdata = [0 => ['type' => 'all', 'name' => __('None')]];
        foreach ($this->departmentlist as $k => $v) {
            $departmentdata[$v['id']] = $v;
        }
        $this->view->assign("flagList", $this->model->getFlagList());
        $this->view->assign("typeList", $this->typelist);
        $this->view->assign("parentList", $departmentdata);
        $this->assignconfig("typeList", $this->typelist);
        $this->assignconfig('typeList', $this->typelist);

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
     * 查看
     */
    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax()) {
        		//如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            $search = $this->request->request("search");
            $type = $this->request->request("type");

            //构造父类select列表选项数据
            $list = [];

            foreach ($this->departmentlist as $k => $v) {
                if ($search) {
                    if ($v['type'] == $type && stripos($v['name'], $search) !== false || stripos($v['nickname'], $search) !== false) {
                        if ($type == "all" || $type == null) {
                            $list = $this->departmentlist;
                        } else {
                            $list[] = $v;
                        }
                    }
                } else {
                    if ($type == "all" || $type == null) {
                        $list = $this->departmentlist;
                    } elseif ($v['type'] == $type) {
                        $list[] = $v;
                    }
                }
            }

            $total = count($list);
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
        		//$this->token();
            $params = $this->request->post("row/a");
            if ($params) {
                $params = $this->preExcludeFields($params);

                if ($this->dataLimit && $this->dataLimitFieldAutoFill) {
                    $params[$this->dataLimitField] = $this->auth->company_id;
                }
                $pdepartment = $this->model->field('name')->where(['id'=>$params['pid'],'company_id'=>$this->auth->company_id])->find();
                $params['pname'] = $pdepartment['name'];
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
        $type = $this->request->param('type');
        $this->view->assign("typeSelected",$type);
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
            $this->token();
            $params = $this->request->post("row/a");
            if ($params) {
                $params = $this->preExcludeFields($params);

                if ($params['pid'] != $row['pid']) {
                    $childrenIds = Tree::instance()->init(collection(\app\admin\model\user\Department::select())->toArray())->getChildrenIds($row['id'], true);
                    if (in_array($params['pid'], $childrenIds)) {
                        $this->error(__('Can not change the parent to child or itself'));
                    }
                }
                $pdepartment = $this->model->field('name')->where(['id'=>$params['pid'],'company_id'=>$this->auth->company_id])->find();
                $params['pname'] = $pdepartment['name'];

                try {
                    //是否采用模型验证
                    if ($this->modelValidate) {
                        $name = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.edit' : $name) : $this->modelValidate;
                        $row->validate($validate);
                    }
                    $result = $row->allowField(true)->save($params);
                    if ($result !== false) {
                        $this->success();
                    } else {
                        $this->error($row->getError());
                    }
                } catch (\think\exception\PDOException $e) {
                    $this->error($e->getMessage());
                } catch (\think\Exception $e) {
                    $this->error($e->getMessage());
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        $this->view->assign("row", $row);
        return $this->view->fetch();
    }
    /**
     * 删除
     */
    public function del($ids = "")
    {
        
        if (!$this->request->isPost()) {
            $this->error(__("Invalid parameters"));
        }
        $ids = $ids ? $ids : $this->request->post("ids");
        if ($ids) {
            $result = 0 ;
            //在删除前先验证是否有子部门
            $departmentmodel = new \app\admin\model\user\Department;
            $result = $departmentmodel->where(['pid'=>['in',$ids]])->select();
            if($result){
                $this->error(__('删除失败，原因是要删除的部门有下属部门，请先删除它的下级部门'));
            }
            //再验证是否有部门人员未删除
            $usermodel = new \app\admin\model\User;
            $result = $usermodel->where(['department_id'=>['in',$ids]])->select();
            if($result){
                $this->error(__('删除失败，原因是要删除的部门有员工，请先删除他们'));
            }
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
                    $count += $v->delete();
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
    /**
     * JSTree交互式树
     *
     * @internal
     */
    public function jstree($type = '')
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);

        $type = $this->request->param("type",$type);
        $list = [];
        $list[]=[
            'id'=>'0', 
            'pId'=>'#',
            'name'=>'全部',
            'open'=>true
        ];
        foreach ($this->departmentlist as $k => $v) {
                $v['pId']=$v['pid'];
                $v['name'] = trim(str_replace($v['spacer'],'',$v['name']));         
                $list[] = $v;    
        }
        if ($this->request->isAjax()) {
            return json($list);
        }else{
            return $list;
        }
    }

}
