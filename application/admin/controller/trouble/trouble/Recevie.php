<?php

namespace app\admin\controller\trouble\trouble;

use app\common\controller\Backend;
use app\admin\model\trouble\trouble\Log as LogModel;
use Think\Db;

/**
 * 隐患告警信息
 *
 * @icon fa fa-circle-o
 */
class Recevie extends Backend
{
    
    /**
     * Recevie模型对象
     * @var \app\admin\model\trouble\trouble\Recevie
     */
    protected $model = null;
    protected $dataLimit = 'personal';
    protected $dataLimitField = 'company_id';

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\trouble\trouble\Recevie;
        $this->view->assign("sourceTypeList", $this->model->getSourceTypeList());
        $this->view->assign("mainStatusList", $this->model->getMainStatusList());
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
        //当前是否为关联查询
        $this->relationSearch = true;
        //设置过滤方法
        $this->request->filter(['strip_tags', 'trim']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $department = new  \app\admin\model\user\Department;
            $area = new  \app\admin\model\trouble\base\Area;
            
            $department_info = $department->where('company_id',$this->auth->company_id)->select();
            $depart_id = array_column($department_info,'id');//部门ID
            $depart_name = array_column($department_info,'name');//部门名称
            $depart_pname = array_column($department_info,'pname');//上级部门
            $name = array_combine($depart_name,$depart_id);
            $pname = array_combine($depart_pname,$depart_id);
            
            $area_info = $area->where('company_id',$this->auth->company_id)->select();
            $area_id = array_column($area_info,'id');
            $area_name = array_column($area_info,'area_name');
            $areaname = array_combine($area_name,$area_id);

            $list = $this->model
                    ->with(['troublepoint','troubletype'])
                    ->where($where)
                    ->where('main_status',0)
                    ->order($sort, $order)
                    ->paginate($limit);

            foreach ($list as $row) {
                $row['department_name'] = array_search($row['troublepoint']['point_department_id'],$name);
                $row['department_pname'] = array_search($row['troublepoint']['point_department_id'],$pname);
                $row['area_name'] = array_search($row['troublepoint']['point_area_id'],$areaname);
                
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
                $params['recevier'] = $this->auth->nickname;//接警人
                $params['main_status'] = 0; //草稿状态
                //加入信息编码生成代码
                $main = $this->model
                ->where('createtime','between time',[date('Y-m-01 00:00:01'),date('Y-m-31 23:59:59')])
                ->where(['company_id'=>$this->auth->company_id]) //出库单
            	 -> order('main_code','desc')->limit(1)->select();
        	       if (count($main)>0) {
        	       $item = $main[0];
        	  	    $code = '0000'.(substr($item['main_code'],8,4)+1);
        	  	    $code = substr($code,strlen($code)-4,4);
        	      	$params['main_code'] = 'YH'.date('Ym').$code;
        	      	} else {
        	  	   	$params['main_code']='YH'.date('Ym').'0001';
        	      	}
                
                
                //完成信息编码生成
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
                    $id = $this->model->id;
                    LogModel::create(['main_id'=>$id,'log_time'=>time(),'log_operator'=>$this->auth->nickname,'log_content'=>'完成手工接警，等待派单...','company_id'=>$this->auth->company_id]);
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
                    $id = $row['id'];
                    LogModel::create(['main_id'=>$id,'log_time'=>time(),'log_operator'=>$this->auth->nickname,'log_content'=>'完成隐患信息编辑，等待派单...','company_id'=>$this->auth->company_id]);
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
                	//添加日志 
                	  $id = $v['id'];
                    LogModel::create(['main_id'=>$id,'log_time'=>time(),'log_operator'=>$this->auth->nickname,'log_content'=>'隐患报警信息作废，任务终止...','company_id'=>$this->auth->company_id]);
                    $count+=1;
                    
                }
                $result = $this->model->where('id','in',$ids)->update(['main_status'=>9]);//完成作废
                Db::commit();
            } catch (PDOException $e) {
                Db::rollback();
                $this->error($e->getMessage());
            } catch (Exception $e) {
                Db::rollback();
                $this->error($e->getMessage());
            }
            if ($result) {
                $this->success();
            } else {
                $this->error(__('No rows were deleted'));
            }
        }
        $this->error(__('Parameter %s can not be empty', 'ids'));
    }
    /**
     * 回收站
     */
    public function recyclebin()
    {
        //当前是否为关联查询
        $this->relationSearch = true;
        //设置过滤方法
        $this->request->filter(['strip_tags', 'trim']);
        if ($this->request->isAjax()) {
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();

            $list = $this->model
                    ->with(['troublepoint','troubletype'])
                    ->where($where)
                    ->where('main_status',9)
                    ->order($sort, $order)
                    ->paginate($limit);

            $result = array("total" => $list->total(), "rows" => $list->items());

            return json($result);
        }
        return $this->view->fetch();
    }
    
    /**
     * 真实删除
     */
    public function destroy($ids = "")
    {
        if (!$this->request->isPost()) {
            $this->error(__("Invalid parameters"));
        }
        $ids = $ids ? $ids : $this->request->post("ids");
        $pk = $this->model->getPk();
        $adminIds = $this->getDataLimitAdminIds();
        if (is_array($adminIds)) {
            $this->model->where($this->dataLimitField, 'in', $adminIds);
        }
        if ($ids) {
            $this->model->where($pk, 'in', $ids);
        }
        $count = 0;
        Db::startTrans();
        try {
            $list = $this->model->where('id','in',$ids)->select();
            foreach ($list as $k => $v) {
                $count += $v->delete(true);
            }
            $log = new LogModel;
            $result = $log->where('main_id','in',$ids)->delete();
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
        $this->error(__('Parameter %s can not be empty', 'ids'));
    }

    /**
     * 还原
     */
    public function restore($ids = "")
    {
        if (!$this->request->isPost()) {
            $this->error(__("Invalid parameters"));
        }
        $ids = $ids ? $ids : $this->request->post("ids");
        $pk = $this->model->getPk();
        $adminIds = $this->getDataLimitAdminIds();
        if (is_array($adminIds)) {
            $this->model->where($this->dataLimitField, 'in', $adminIds);
        }
        //if ($ids) {
        //    $this->model->where($pk, 'in', $ids);
        //}
        $list = $this->model->where($pk, 'in', $ids)->select();
        $count = 0;
        Db::startTrans();
            try {
                foreach ($list as $k => $v) {
                	//添加日志 
                	  $id = $v['id'];
                    LogModel::create(['main_id'=>$id,'log_time'=>time(),'log_operator'=>$this->auth->nickname,'log_content'=>'隐患报警信息已恢复，等待派单...','company_id'=>$this->auth->company_id]);
                    $count +=1 ;
                    
                }
                $result = $this->model->where('id','in',$ids)->update(['main_status'=>0]);//完成作废
                Db::commit();
            } catch (PDOException $e) {
                Db::rollback();
                $this->error($e->getMessage());
            } catch (Exception $e) {
                Db::rollback();
                $this->error($e->getMessage());
            }
        if ($result) {
            $this->success();
        }
        $this->error(__('No rows were updated'));
    }

}
