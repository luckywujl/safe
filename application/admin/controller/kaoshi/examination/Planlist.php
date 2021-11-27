<?php

namespace app\admin\controller\kaoshi\examination;

use app\common\controller\Backend;

/**
 * 考场管理
 *
 * @icon fa fa-circle-o
 */
class Planlist extends Backend
{
    
    /**
     * Planlist模型对象
     * @var \app\admin\model\kaoshi\examination\Planlist
     */
    protected $model = null;
    protected $dataLimit = 'personal';
	 protected $dataLimitField = 'company_id';
    protected $searchFields = 'id,username,nickname';
    protected $noNeedRight = ['index','getplan','selectplan','delplan'];

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\kaoshi\examination\Planlist;
        $this->view->assign("typeList", $this->model->getTypeList());
        $this->view->assign("limitList", $this->model->getLimitList());
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
    public function index($ids = null)
    {
        //当前是否为关联查询
        $this->relationSearch = true;
        //设置过滤方法
        $this->request->filter(['strip_tags', 'trim']);
        $id = $this->request->param('user_id');//接收过滤条件
        $user = new \app\admin\model\User;
        $user_info = $user->where('id',$ids)->find();
        $user_nickname = $user_info['nickname'];
        $ids = ','.$ids.',';
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $userplan = new \app\admin\model\kaoshi\examination\KaoshiUserPlan;
            $plan_id = $userplan->where('user_id','in',$ids)->where(['company_id'=>$this->auth->company_id])->column('plan_id');

            
            $total = $this->model
               
                ->where($where) 
                ->where(['planlist.company_id'=>$this->auth->company_id])  
                ->where('id','in',$plan_id)
                ->order($sort, $order)
                ->count();

            $main = $this->model
               
                ->where($where) 
                ->where('planlist.company_id',$this->auth->company_id)
                ->where('id','in',$plan_id)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();
            
           

          
            $result = array("total" => $total, "rows" => $main,"extend"=>$user_nickname);
            return json($result);
     
        }
        $this->assignconfig("ids", $id);
        return $this->view->fetch();
    }
    
    /**
     * 查看
     */
    public function getplan($ids = null)
    {
        //当前是否为关联查询
        $this->relationSearch = true;
        //设置过滤方法
        $this->request->filter(['strip_tags', 'trim']);
        $id = $this->request->param('user_id');//接收过滤条件
        $user = new \app\admin\model\User;
        $user_info = $user->where('id',$ids)->find();
        $user_nickname = $user_info['nickname'];
        $ids = ','.$id.',';
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $userplan = new \app\admin\model\kaoshi\examination\KaoshiUserPlan;
            $plan_id = $userplan->where('user_id','in',$ids)->where(['company_id'=>$this->auth->company_id])->column('plan_id');

            
            $total = $this->model
               // ->with(['trainingcategory'])
                ->where($where) 
                ->where(['planlist.company_id'=>$this->auth->company_id])  
                ->where('id','not in',$plan_id)
                ->order($sort, $order)
                ->count();

            $main = $this->model
               // ->with(['trainingcategory'])
                ->where($where) 
                ->where('planlist.company_id',$this->auth->company_id)
                ->where('id','not in',$plan_id)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();
            
           

          
            $result = array("total" => $total, "rows" => $main,"extend"=>$user_nickname);
            return json($result);
     
        }
        $this->assignconfig("ids", $ids);
        return $this->view->fetch();
    }
    /**
     * 选择考试
     */
    public function selectplan()
    {
      
        if ($this->request->isPost()) {
          	$params = $this->request->param();//接收过滤条件
          	$result = '';
          	//$user = explode(",",$params['ids_user']);//因为传递过来的ids_user是字符不是数组，所以要用explode将其转为数组
          	//$main = $params['ids_main'];  //将传送过来的ids_main改名为$main
          	//将原main表中的user_ids和id读到二维数组中，不用每次都读表
          	
          	
          	$user_plan = new \app\admin\model\kaoshi\examination\KaoshiUserPlan;
          	$plan = $params['ids_plan'];
          	$plan_data =[];
          	foreach($plan as $k => $v){
          		$data = [];
          		$data['user_id'] = $params['ids_user'];
          		$data['plan_id'] = $v;
          		$data['status'] =0;
          		$data['company_id'] =$this->auth->company_id;
          		$plan_data[] = $data;
          	
          	}
          	
            if($plan_data) {
           	 	$result = $user_plan->saveall($plan_data);
           	 	if($result) {
          		  $this->success('完成！');
        			}
             	$this->error('保存失败');
         	} else {
         		$this->success('没有被修改的信息');
         	}
         
       }
       
    }
    
/**
     * 退出考试
     */
    public function delplan()
    {
      
        if ($this->request->isPost()) {
          	$params = $this->request->param();//接收过滤条件
          	$result = '';
          	//$user = explode(",",$params['ids_user']);//因为传递过来的ids_user是字符不是数组，所以要用explode将其转为数组
          	//$main = $params['ids_main'];  //将传送过来的ids_main改名为$main
          	//将原main表中的user_ids和id读到二维数组中，不用每次都读表
          	$user_plan = new \app\admin\model\kaoshi\examination\KaoshiUserPlan;
          	//$plan = implode(',',$params['ids_plan']);
          	//$user = implode(',',$params['ids_user']);
           	$result = $user_plan->where('plan_id','in',$params['ids_plan'])->where(['status'=>0,'user_id'=>$params['ids_user']])->delete();
           	if($result) {
          		$this->success('完成！');
        		}
             	$this->error('退出失败');
         	
         
       }
       
    }


}
