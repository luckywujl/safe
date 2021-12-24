<?php

namespace app\admin\controller\trouble\trouble;

use app\common\controller\Backend;
use app\admin\model\trouble\trouble\Log as LogModel;

use app\common\library\Auth;
use Think\Db;
use fast\Tree;
use Exception;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use think\exception\PDOException;
use think\exception\ValidateException;

/**
 * 隐患告警信息
 *
 * @icon fa fa-circle-o
 */
class Recevie extends Backend
{
    
    /**
     * Dispatch模型对象
     * @var \app\admin\model\trouble\trouble\Recevie
     */
    protected $model = null;
    protected $dataLimit = 'personal';
    protected $dataLimitField = 'company_id';
    protected $noNeedRight = ['getlog'];

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\trouble\trouble\Recevie;
        $this->view->assign("sourceTypeList", $this->model->getSourceTypeList());
        $this->view->assign("mainStatusList", $this->model->getMainStatusList());
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
            $name = array_combine($depart_id,$depart_name);
            $pname = array_combine($depart_id,$depart_pname,);
            
            $area_info = $area->where('company_id',$this->auth->company_id)->select();
            $area_id = array_column($area_info,'id');
            $area_name = array_column($area_info,'area_name');
            $areaname = array_combine($area_id,$area_name);

            $list = $this->model
                    ->with(['troublepoint','troubletype'])
                    ->where($where)
                    ->where('main_status','in','0,1')
                    ->order($sort, $order)
                    ->paginate($limit);

            foreach ($list as $row) {
                $row['department_name'] = $name[$row['troublepoint']['point_department_id']];
                $row['department_pname'] = $pname[$row['troublepoint']['point_department_id']];
                $row['area_name'] = $areaname[$row['troublepoint']['point_area_id']];
                
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
        	
        $user = new \app\admin\model\User;
        $user_info = $user->field('id,nickname,jobnumber,mobile')->where(['company_id'=>$this->auth->company_id])->order('id','asc')->select();
        $user_id = array_column($user_info,'id');
        $user_nickname = array_column($user_info,'nickname');
        $user_jobnumber = array_column($user_info,'jobnumber');
        $user_mobile = array_column($user_info,'mobile');
        $userid = array_combine($user_id,$user_nickname);
        $jobnumber = array_combine($user_jobnumber,$user_nickname);
        $mobile = array_combine($user_mobile,$user_nickname);
        
            $params = $this->request->post("row/a");
            if ($params) {
                $params = $this->preExcludeFields($params);

                if ($this->dataLimit && $this->dataLimitFieldAutoFill) {
                    $params[$this->dataLimitField] = $this->auth->company_id;
                }
                $params['recevier'] = $this->auth->nickname;//接警人
                $params['main_status'] = 0; //草稿状态
               
        	  	    $params['main_code']=$this->getcode();
        	  	    $params['process_pic'] = '';
        	       $params['finish_pic'] = '';
        	    
                if($params['informer_name']=='') {
                    		$params['informer_name'] = array_search($this->auth->nickname,$jobnumber).'-'.$this->auth->nickname.'('.array_search($this->auth->nickname,$mobile).')';//转换一下报警人姓名，加上工号和电话号码
                    		$params['informer'] =array_search($this->auth->nickname,$userid); //根据当前操作姓名转换成报警人ID
                    }else {
                    		$params['informer'] =array_search($params['informer_name'],$userid); //根据当前操作姓名转换成报警人ID
                    		$params['informer_name'] = array_search($params['informer_name'],$jobnumber).'-'.$params['informer_name'].'('.array_search($params['informer_name'],$mobile).')';//转换一下报警人姓名，加上工号和电话号码	
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
                if($params['informer_name']=='') { //如果不填写报警人信息，则用当前用户
                	$params['informer'] = $this->auth->user_id;
                	$params['informer_name'] = $this->auth->username.'-'.$this->auth->nickname.'('.$this->auth->mobile.')';
                } 
                
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
        $row['log'] = $this->getlog($row['id']);
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
                	  if($v['main_status']==1) {
                	  	  $this->error(__('选中的删除列表中有已接警的隐患信息，不能删除！'));
                	  }
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
    /**
    *接警
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
            $plan_info = array_combine($type_id,$plan_content);
            
            //完成部门信息封装，便 于快速查找
            $department = new  \app\admin\model\user\Department;
            $depart_info = $department->where('company_id',$this->auth->company_id)->select();
            $depart_id = array_column($depart_info,'id');//部门ID
            $depart_leader = array_column($depart_info,'leader');//部门负责人
            $depart_person = array_column($depart_info,'person');//部门安全员
            $depart_pid = array_column($depart_info,'pid');//上级部门ID
            
            $leader = array_combine($depart_id,$depart_leader);
            $person = array_combine($depart_id,$depart_person);
            $pid = array_combine($depart_id,$depart_pid);//上级部门ID
            
            //完成隐患点封装，便于快速查找
            $point = new  \app\admin\model\trouble\base\Point;
            $point_info = $point->where('company_id',$this->auth->company_id)->select();
            $point_id = array_column($point_info,'id');
            $point_department_id = array_column($point_info,'point_department_id');
            $department_info = array_combine($point_id,$point_department_id);
            
            $data = [];

            $result = 0;
            Db::startTrans();
            try {
                foreach ($list as $k => $v) {
                	if($v['main_status']==0) {
                		$item =[];
                	  //添加日志 
                	  $id = $v['id'];
                	  
                    LogModel::create(['main_id'=>$id,'log_time'=>time(),'log_operator'=>$this->auth->nickname,'log_content'=>'隐患报警信息已经完成接警，并已派单，等待处理...','company_id'=>$this->auth->company_id]);
                    //派单操作，添加责任人，进入流转环节 1、先确定信息点所属部门，隐患等级，获得预案内容 2、根据预案内容，完成liabler（责任人字段内容）3、更新责任人内容
                    //1、获取预案内容
                    
                    $plan = $plan_info[$v['trouble_type_id']];//根据键寻值
                    
                    //获取部门ID
                    $department_id = $department_info[$v['point_id']];//根据键寻值
                    //获取部门负责人及安全员以及上级部门的负责人和上级部门的安全员
                    $leader_d = explode(',',$leader[$department_id]);//本部门负责人
                    $person_d = explode(',',$person[$department_id]);//本部门安全员
                    $department_pid = $pid[$department_id];
                    $leader_p = explode(',',$leader[$department_pid]);//上级负责人
                    $person_p = explode(',',$person[$department_pid]);//上级安全员
                    //定义一个数组，用于存放liabler内容
                    $liabler = [];
                    $liabler=array_merge($liabler,$person_d);//责任人由部门安全员负责，下面根据不同一隐患等级，抄送不同的人
                    $liabler=array_merge($liabler,$leader_d);
                    
                    $insider =[];
                    //$liabler[] ='0';
                    //循环运行，根据$plan内容，将负责人添加到liabler中
                    if(substr($plan, 0, 1)=='1')$insider=array_merge($insider,$person_d);
                    if(substr($plan, 1, 1)=='1')$insider=array_merge($insider,$leader_d);
                    if(substr($plan, 2, 1)=='1')$insider=array_merge($insider,$person_p);
                    if(substr($plan, 3, 1)=='1')$insider=array_merge($insider,$leader_p);
                    
                    
                   
                    $liabler_s = trim(implode(',',$liabler),',');
                    $insider_s = trim(implode(',',$insider),',');
                    
                    //更新表 因为锁表状态，改用saveall
                    //$result = $this->model->where('id',$id)->update(['main_status'=>1,'updatetime'=>time(),'firstduration'=>$firstduration,'liabler'=>$liabler_s,'insider'=>$insider_s,'recevier'=>$this->auth->nickname]);//完成派单,更新接单时间以及首次跟进时长
                    $item['id'] = $v['id'];
                    $item['main_status'] = 1;
                    $item['updatetime'] = time();
                    $item['firstduration'] = round((time()-$v['createtime'])/3600,2);
                    $item['liabler'] = $liabler_s;
                    $item['insider'] = $insider_s;
                    $item['recevier'] = $this->auth->nickname;
                    $data[] = $item;
                    
                 }  
                }
                $result = $this->model->allowField(true)->saveall($data);
                Db::commit();
            } catch (PDOException $e) {
                Db::rollback();
                $this->error($e->getMessage());
            } catch (Exception $e) {
                Db::rollback();
                $this->error($e->getMessage());
            }
            if ($result) {
            	$this->success('接警成功！');
                //$this->success($id.'-'.$type_id.'-'.$plan.'-'.$department_id.'-'.array_search($department_id,$leader).'-'.array_search($department_id,$person).'-'.array_search($department_pid,$leader).'-'.array_search($department_pid,$person));
            } else {
                $this->error('接警完成！');
            }
        }
        $this->error(__('Parameter %s can not be empty', 'ids'));
    }
    /**
    *取消接警
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
                $result = $this->model->where('id','in',$ids)->update(['main_status'=>0,'liabler'=>'','insider'=>'']);//完成取消派单,并清空任务接收人
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
    
    public function getlog($id)
    {
        $log = new LogModel;
        $log_info = $log->where('main_id',$id)->order('id')->select();
        $result = '';
        foreach($log_info as $k=>$v){
        		$result = $result.date("Y-m-d H:i:s",$v['log_time']).':('.$v['log_operator'].')'.$v['log_content']."\n";
        }
        return $result;
    }
    
    /**
     * 导入
     */
    public function import()
    {
    	  $point = new \app\admin\model\trouble\base\Point;
        $point_info = $point->field('id,point_code')->where(['company_id'=>$this->auth->company_id])->order('id', 'asc')->select(); 
        $point_code = array_column($point_info , 'point_code');//将信息点编码装入一维数组，免得重复读表
        $point_id = array_column($point_info , 'id');//获取部门ID，主键
        $point_info = array_combine($point_id,$point_code);//将两个一维数组组装成键名=》键值形式
        
        $type = new \app\admin\model\trouble\base\Type;
        $type_info = $type->field('id,trouble_type')->where(['company_id'=>$this->auth->company_id])->order('id', 'asc')->select(); 
        $trouble_type = array_column($type_info , 'trouble_type');//将区域名称装入一维数组，免得重复读表
        $type_id = array_column($type_info , 'id');//获取区载ID，主键
        $type_info = array_combine($type_id,$trouble_type);//将两个一维数组组装成键名=》键值形式
        
        $user = new \app\admin\model\User;
        $user_info = $user->field('id,nickname,jobnumber,mobile')->where(['company_id'=>$this->auth->company_id])->order('id','asc')->select();
        $user_id = array_column($user_info,'id');
        $user_nickname = array_column($user_info,'nickname');
        $user_jobnumber = array_column($user_info,'jobnumber');
        $user_mobile = array_column($user_info,'mobile');
        $userid = array_combine($user_id,$user_nickname);
        $jobnumber = array_combine($user_jobnumber,$user_nickname);
        $mobile = array_combine($user_mobile,$user_nickname);
        
        $type_A = '0,1,2';
        $type_B = '路人告警,人工巡查,安全检查';
        $source_type = array_combine(explode(',',$type_A),explode(',',$type_B));
        
        $file = $this->request->request('file');
        if (!$file) {
            $this->error(__('Parameter %s can not be empty', 'file'));
        }
        $filePath = ROOT_PATH . DS . 'public' . DS . $file;
        if (!is_file($filePath)) {
            $this->error(__('No results were found'));
        }
        //实例化reader
        $ext = pathinfo($filePath, PATHINFO_EXTENSION);
        if (!in_array($ext, ['csv', 'xls', 'xlsx'])) {
            $this->error(__('Unknown data format'));
        }
        if ($ext === 'csv') {
            $file = fopen($filePath, 'r');
            $filePath = tempnam(sys_get_temp_dir(), 'import_csv');
            $fp = fopen($filePath, "w");
            $n = 0;
            while ($line = fgets($file)) {
                $line = rtrim($line, "\n\r\0");
                $encoding = mb_detect_encoding($line, ['utf-8', 'gbk', 'latin1', 'big5']);
                if ($encoding != 'utf-8') {
                    $line = mb_convert_encoding($line, 'utf-8', $encoding);
                }
                if ($n == 0 || preg_match('/^".*"$/', $line)) {
                    fwrite($fp, $line . "\n");
                } else {
                    fwrite($fp, '"' . str_replace(['"', ','], ['""', '","'], $line) . "\"\n");
                }
                $n++;
            }
            fclose($file) || fclose($fp);

            $reader = new Csv();
        } elseif ($ext === 'xls') {
            $reader = new Xls();
        } else {
            $reader = new Xlsx();
        }

        //导入文件首行类型,默认是注释,如果需要使用字段名称请使用name
        $importHeadType = isset($this->importHeadType) ? $this->importHeadType : 'comment';

        $table = $this->model->getQuery()->getTable();
        $database = \think\Config::get('database.database');
        $fieldArr = [];
        $list = db()->query("SELECT COLUMN_NAME,COLUMN_COMMENT FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = ? AND TABLE_SCHEMA = ?", [$table, $database]);
        foreach ($list as $k => $v) {
            if ($importHeadType == 'comment') {
                $fieldArr[$v['COLUMN_COMMENT']] = $v['COLUMN_NAME'];
            } else {
                $fieldArr[$v['COLUMN_NAME']] = $v['COLUMN_NAME'];
            }
        }

        //加载文件
        $insert = [];
        try {
            if (!$PHPExcel = $reader->load($filePath)) {
                $this->error(__('Unknown data format'));
            }
            $currentSheet = $PHPExcel->getSheet(0);  //读取文件中的第一个工作表
            $allColumn = $currentSheet->getHighestDataColumn(); //取得最大的列号
            $allRow = $currentSheet->getHighestRow(); //取得一共有多少行
            $maxColumnNumber = Coordinate::columnIndexFromString($allColumn);
            $fields = [];
            for ($currentRow = 1; $currentRow <= 1; $currentRow++) {
                for ($currentColumn = 1; $currentColumn <= $maxColumnNumber; $currentColumn++) {
                    $val = $currentSheet->getCellByColumnAndRow($currentColumn, $currentRow)->getValue();
                    $fields[] = $val;
                }
            }

            for ($currentRow = 2; $currentRow <= $allRow; $currentRow++) {
                $values = [];
                for ($currentColumn = 1; $currentColumn <= $maxColumnNumber; $currentColumn++) {
                    $val = $currentSheet->getCellByColumnAndRow($currentColumn, $currentRow)->getValue();
                    $values[] = is_null($val) ? '' : $val;
                }
                $row = [];
                $temp = array_combine($fields, $values);
                foreach ($temp as $k => $v) {
                    if (isset($fieldArr[$k]) && $k !== '') {
                        $row[$fieldArr[$k]] = $v;
                    }
                }
                if ($row) {
                    $insert[] = $row;
                }
            }
        } catch (Exception $exception) {
            $this->error($exception->getMessage());
        }
        if (!$insert) {
            $this->error(__('No rows were updated'));
        }

        try {
            //是否包含admin_id字段
            $has_admin_id = false;
            foreach ($fieldArr as $name => $key) {
                if ($key == 'company_id') {
                    $has_admin_id = true;
                    break;
                }
            }
            if ($has_admin_id) {
                $auth = Auth::instance();
                $maincode =  $this->getcode();
                foreach ($insert as &$val) {
                    if (!isset($val['company_id']) || empty($val['company_id'])) {
                        $val['company_id'] = $this->auth->company_id;
                    }
                    $val['main_code'] = $maincode;
                    $val['point_id'] = array_search($val['point_id'],$point_info);//将信息点编号转为信息点ID
                    $val['createtime'] = time();//创建时间
                    $val['updatetime'] = time();//编辑时间
                    $val['process_pic'] = '';//导入时将处理照片和完结照片置空，以保证表格中不显示默认图片
        	           $val['finish_pic'] = '';
                    $val['source_type'] = (int)array_search($val['source_type'],$source_type);//信息来源
                    //$val['source_type'] = 0;
                    $val['trouble_type_id'] = array_search($val['trouble_type_id'],$type_info);//隐患类型
                    $val['main_status'] = 0;//草稿
                    if($val['informer_name']=='') {
                    		$val['informer_name'] = array_search($this->auth->nickname,$jobnumber).'-'.$this->auth->nickname.'('.array_search($this->auth->nickname,$mobile).')';//转换一下报警人姓名，加上工号和电话号码
                    		$val['informer'] =array_search($this->auth->nickname,$userid); //根据当前操作姓名转换成报警人ID
                    }else {
                    		$val['informer'] =array_search($val['informer_name'],$userid); //根据当前操作姓名转换成报警人ID
                    		$val['informer_name'] = array_search($val['informer_name'],$jobnumber).'-'.$val['informer_name'].'('.array_search($val['informer_name'],$mobile).')';//转换一下报警人姓名，加上工号和电话号码	
                    }
                    //将maincode加一个上去
                    $maincode = 'YH'.date('Ym').substr('0000'.(substr($maincode,8,4)+1),strlen('0000'.(substr($maincode,8,4)+1))-4,4);
                }
            }
            $this->model->saveAll($insert);
        } catch (PDOException $exception) {
            $msg = $exception->getMessage();
            if (preg_match("/.+Integrity constraint violation: 1062 Duplicate entry '(.+)' for key '(.+)'/is", $msg, $matches)) {
                $msg = "导入失败，包含【{$matches[1]}】的记录已存在";
            };
            $this->error($msg);
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }

        $this->success();
    }
    public function getcode() {
    	         //加入信息编码生成代码
              $main = $this->model
                ->where('createtime','between time',[date('Y-m-01 00:00:01'),date('Y-m-31 23:59:59')])
                ->where(['company_id'=>$this->auth->company_id]) //出库单
            	 -> order('main_code','desc')->limit(1)->select();
        	    if (count($main)>0) {
        	       $item = $main[0];
        	  	    $code = '0000'.(substr($item['main_code'],8,4)+1);
        	  	    $code = substr($code,strlen($code)-4,4);
        	       $code = 'YH'.date('Ym').$code;
        	     } else {
        	  	    $code='YH'.date('Ym').'0001';
        	     }
              return $code;
   }
   
    

}
