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
class Dispatch extends Backend
{
    
    /**
     * Dispatch模型对象
     * @var \app\admin\model\trouble\trouble\Dispatch
     */
    protected $model = null;
    protected $dataLimit = 'personal';
    protected $dataLimitField = 'company_id';

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\trouble\trouble\Dispatch;
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
                    ->where('main_status','in','0,1')
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
    *派单
    */
    public function verify($ids="")
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
            //完成隐患类型封装，便于快速查找
            $type = new  \app\admin\model\trouble\base\Type;
            $type_info = $type->where('company_id',$this->auth->company_id)->select();
            $type_id = array_column($type_info,'id');
            $plan_content = array_column($type_info,'plan_content');
            $plan_info = array_combine($plan_content,$type_id);
            
            //完成部门信息封装，便 于快速查找
            $department = new  \app\admin\model\user\Department;
            $depart_info = $department->where('company_id',$this->auth->company_id)->select();
            $depart_id = array_column($depart_info,'id');//部门ID
            $depart_leader = array_column($depart_info,'leader');//部门负责人
            $depart_person = array_column($depart_info,'person');//部门安全员
            $depart_pid = array_column($depart_info,'pid');//上级部门ID
            
            $leader = array_combine($depart_leader,$depart_id);
            $person = array_combine($depart_person,$depart_id);
            $pid = array_combine($depart_pid,$depart_id);//上级部门ID
            
            //完成隐患点封装，便于快速查找
            $point = new  \app\admin\model\trouble\base\Point;
            $point_info = $point->where('company_id',$this->auth->company_id)->select();
            $point_id = array_column($point_info,'id');
            $point_department_id = array_column($point_info,'point_department_id');
            $department_info = array_combine($point_department_id,$point_id);

            $count = 0;
            Db::startTrans();
            try {
                foreach ($list as $k => $v) {
                	if($v['main_status']==0) {
                	  //添加日志 
                	  $id = $v['id'];
                    LogModel::create(['main_id'=>$id,'log_time'=>time(),'log_operator'=>$this->auth->nickname,'log_content'=>'隐患报警信息已经完成接警，并已派单，即将进入流转处理环节...','company_id'=>$this->auth->company_id]);
                    //派单操作，添加责任人，进入流转环节 1、先确定信息点所属部门，隐患等级，获得预案内容 2、根据预案内容，完成liabler（责任人字段内容）3、更新责任人内容
                    //1、获取预案内容
                    
                    $plan = array_search($v['trouble_type_id'],$plan_info);
                    //获取部门ID
                    $department_id = array_search($v['point_id'],$department_info);
                    //获取部门负责人及安全员以及上级部门的负责人和上级部门的安全员
                    $leader_d = explode(',',array_search($department_id,$leader));//本部门负责人
                    $person_d = explode(',',array_search($department_id,$person));//本部门安全员
                    $department_pid = array_search($department_id,$pid);
                    $leader_p = explode(',',array_search($department_pid,$leader));//上级负责人
                    $person_p = explode(',',array_search($department_pid,$person));//上级安全员
                    //定义一个数组，用于存放liabler内容
                    $liabler = [];
                    //$liabler[] ='0';
                    //循环运行，根据$plan内容，将负责人添加到liabler中
                    if(substr($plan, 0, 1)=='1')$liabler=array_merge($liabler,$person_d);
                    if(substr($plan, 1, 1)=='1')$liabler=array_merge($liabler,$leader_d);
                    if(substr($plan, 2, 1)=='1')$liabler=array_merge($liabler,$person_p);
                    if(substr($plan, 3, 1)=='1')$liabler=array_merge($liabler,$leader_p);
                    
                    
                   
                    $liabler_s = trim(implode(',',$liabler),',');
                    //更新表
                    $result = $this->model->where('id',$id)->update(['main_status'=>1,'liabler'=>$liabler_s]);//完成派单
                    $count+=1; 
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
            if ($result) {
            	$this->success('派单成功！');
                //$this->success($id.'-'.$type_id.'-'.$plan.'-'.$department_id.'-'.array_search($department_id,$leader).'-'.array_search($department_id,$person).'-'.array_search($department_pid,$leader).'-'.array_search($department_pid,$person));
            } else {
                $this->error($liabler);
            }
        }
        $this->error(__('Parameter %s can not be empty', 'ids'));
    }
    /**
    *取消派单
    */
    public function cancelverify($ids="")
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
                	if($v['main_status']==1) {
                	//添加日志 
                	  $id = $v['id'];
                    LogModel::create(['main_id'=>$id,'log_time'=>time(),'log_operator'=>$this->auth->nickname,'log_content'=>'隐患报警信息已经取消派单，等待重新派单...','company_id'=>$this->auth->company_id]);
                    $count+=1; 
                 }  
                }
                $result = $this->model->where('id','in',$ids)->update(['main_status'=>0]);//完成取消派单
                Db::commit();
            } catch (PDOException $e) {
                Db::rollback();
                $this->error($e->getMessage());
            } catch (Exception $e) {
                Db::rollback();
                $this->error($e->getMessage());
            }
            if ($result) {
                $this->success('取消派单！');
            } else {
                $this->error(__('没有隐患信息被派单！'));
            }
        }
        $this->error(__('Parameter %s can not be empty', 'ids'));
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
                    LogModel::create(['main_id'=>$id,'log_time'=>time(),'log_operator'=>$this->auth->nickname,'log_content'=>'完成隐患信息内容修改，未改变隐患信息状态...','company_id'=>$this->auth->company_id]);
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

}
