<?php

namespace app\admin\controller\trouble\base;

use app\common\controller\Backend;
use think\Db;
use fast\Tree;
/**
 * 隐患现象
 *
 * @icon fa fa-circle-o
 */
class Type extends Backend
{
    
    /**
     * Type模型对象
     * @var \app\admin\model\trouble\base\Type
     */
    protected $model = null;
    protected $dataLimit = 'personal';
	 protected $dataLimitField = 'company_id';
	 protected $noNeedRight = ['jstree'];
	 protected $searchFields =['name'];
	 

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\trouble\base\Type;
        $info = $this->model->where('company_id',$this->auth->company_id)->select();
        $name = array_column($info,'name');
        $id = array_column($info,'id');
        $idtoname = array_combine($id,$name);
       
       
        $tree = Tree::instance();
        $tree->init(collection($this->model->where(['company_id'=>$this->auth->company_id,'level'=>0])->order('id asc')->select())->toArray(), 'pid');
        $this->kindlist = $tree->getTreeList($tree->getTreeArray(0), 'name');
        $kinddata = [];
        foreach ($this->kindlist as $k => $v) {
            $kinddata[$v['id']] = $v;
        }
        $this->view->assign("kindList", $kinddata);
        $this->assignconfig("kindList", $kinddata);

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

        $this->request->filter(['strip_tags', 'trim']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $info = $this->model->where('company_id',$this->auth->company_id)->select();
            $name = array_column($info,'name');
            $id = array_column($info,'id');
            $idtoname = array_combine($id,$name);

            $list = $this->model
                ->where($where)
                ->where(['level'=>1])  //仅显示级别为1的作为类型
                ->order($sort, $order)
                ->paginate($limit);
            foreach($list as $k => $v)
            {
              $v['pname'] = $idtoname[$v['pid']];
            }

            $result = array("total" => $list->total(), "rows" => $list->items());

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
            if ($params) {
                $params = $this->preExcludeFields($params);

                if ($this->dataLimit && $this->dataLimitFieldAutoFill) {
                    $params[$this->dataLimitField] = $this->auth->company_id;
                }
                $params['level'] =1;
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
        $pid = $this->request->request("pid",'');
        $this->view->assign("pid", $pid);
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
            if ($params) {
                $params = $this->preExcludeFields($params);
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
            
            //再验证是否有该分类下的隐患类别未删除
            
            $result = $this->model->where(['pid'=>['in',$ids]])->select();
            if($result){
                $this->error(__('删除失败，原因是要删除的类型下有隐患现象，请先删除他们'));
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
    public function jstree()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        $list = [];
        $list[]=[
            'id'=>'0', 
            'pId'=>'#',
            'name'=>'全部',
            'open'=>true
        ];
        
        foreach ($this->kindlist as $k => $v) {
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
