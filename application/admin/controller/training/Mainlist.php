<?php

namespace app\admin\controller\training;

use app\common\controller\Backend;
use app\common\library\Auth;
use Think\Db;
use fast\Tree;

/**
 * 培训管理
 *
 * @icon fa fa-circle-o
 */
class Mainlist extends Backend
{
    
    /**
     * Mainlist模型对象
     * @var \app\admin\model\training\Mainlist
     */
    protected $model = null;
    protected $dataLimit = 'personal';
	 protected $dataLimitField = 'company_id';
    protected $searchFields = 'id,username,nickname';
    protected $noNeedRight = ['index','getmain','selectmain','delmain'];

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\training\Mainlist;
        $this->view->assign("statusList", $this->model->getStatusList());
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
        $ids = $this->request->param('user_id');//接收过滤条件
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
            

            
            $total = $this->model
                ->with(['trainingcategory'])
                ->where($where) 
                ->where(['mainlist.company_id'=>$this->auth->company_id])  
                ->order($sort, $order)
                ->count();

            $main = $this->model
                ->with(['trainingcategory'])
                ->where($where) 
                ->where('mainlist.company_id',$this->auth->company_id)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();
            
            $list = [];
            foreach ($main as $k => $v) {
                $user_ids = ','.$v['user_ids'].',';
               if(strstr($user_ids,$ids)) {
               	$list[] = $v;
               }   
            }    

            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list,"extend"=>$user_nickname);
            return json($result);
     
        }
        $this->assignconfig("ids", $ids);
        return $this->view->fetch();
    }
    
    /**
     * 查看
     */
    public function getmain()
    {
        //当前是否为关联查询
        $this->relationSearch = true;
        //设置过滤方法
        $this->request->filter(['strip_tags', 'trim']);
        $ids = $this->request->param('user_id');//接收过滤条件
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
            

            
            $total = $this->model
                ->with(['trainingcategory'])
                ->where($where) 
                ->where(['mainlist.company_id'=>$this->auth->company_id])  
                ->order($sort, $order)
                ->count();

            $main = $this->model
                ->with(['trainingcategory'])
                ->where($where) 
                ->where('mainlist.company_id',$this->auth->company_id)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();
            
            $list = [];
            foreach ($main as $k => $v) {
                $user_ids = ','.$v['user_ids'].',';
               if(!strstr($user_ids,$ids)) {
               	$list[] = $v;
               }   
            }    

            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list,"extend"=>$user_nickname);
            return json($result);
     
        } 
    }
    /**
     * 选择培训
     */
    public function selectmain()
    {
      
        if ($this->request->isPost()) {
          	$params = $this->request->param();//接收过滤条件
          	$result = '';
          	//$user = explode(",",$params['ids_user']);//因为传递过来的ids_user是字符不是数组，所以要用explode将其转为数组
          	//$main = $params['ids_main'];  //将传送过来的ids_main改名为$main
          	//将原main表中的user_ids和id读到二维数组中，不用每次都读表
          	$main = new \app\admin\model\training\Main_s;
          	$main_info = $main->field('id,user_ids')->where(['id'=>['in',$params['ids_main']],'company_id'=>$this->auth->company_id])	->select();
          	$main_data =[];
          	foreach ($main_info as $k => $v) {
          		$data = [];
          		$data['id'] = $v['id'];
          		//$data['user_ids'] = trim($params['ids_user'],',');
          		$data['user_ids'] =implode(',',array_merge(explode(',',$v['user_ids']),explode(',',trim($params['ids_user'],','))));
          		$main_data[] = $data;
            }
            if($main_data) {
           	 	$result = $main->saveall($main_data);
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
     * 退出培训
     */
    public function delmain()
    {
      
        if ($this->request->isPost()) {
          	$params = $this->request->param();//接收过滤条件
          	$result = '';
          	//$user = explode(",",$params['ids_user']);//因为传递过来的ids_user是字符不是数组，所以要用explode将其转为数组
          	//$main = $params['ids_main'];  //将传送过来的ids_main改名为$main
          	//将原main表中的user_ids和id读到二维数组中，不用每次都读表
          	$main = new \app\admin\model\training\Main_s;
          	$main_info = $main->field('id,user_ids')->where(['id'=>['in',$params['ids_main']],'company_id'=>$this->auth->company_id])	->select();
          	$main_data =[];
          	foreach ($main_info as $k => $v) {
          		$data = [];
          		$data['id'] = $v['id'];
          		$data['user_ids'] =implode(',',array_diff(explode(',',$v['user_ids']),explode(',',$params['ids_user']))); 
          		$main_data[] = $data;
            }
            if($main_data) {
           	 	$result = $main->saveall($main_data);
           	 	if($result) {
          		  $this->success('完成！');
        			}
             	$this->error('保存失败');
         	} else {
         		$this->success('没有被修改的信息');
         	}
         
       }
       
    }

}
