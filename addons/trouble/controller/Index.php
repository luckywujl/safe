<?php

namespace addons\trouble\controller;

use addons\trouble\model\Main as MainModel;
use addons\trouble\model\Log as LogModel;
use app\common\model\User;

//use app\common\model\User;

class Index extends Base
{

    /**
     * 无需登录的方法,同时也就不需要鉴权了
     * @var array
     */
    protected $noNeedLogin = [];

    /**
     * 无需鉴权的方法,但需要登录
     * @var array
     */
    protected $noNeedRight = ['*'];
    protected $relationSearch = true;
    protected $group_id = null;
    protected $department_id = null;
    protected $user_id = null;
    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\kaoshi\User;;
        //$this->group_id = User::where('id', $this->auth->id)->value('group_id');
        $this->user_id = $this->auth->id;
        $this->company_id = $this->auth->company_id;
        
       // $type = $materials_type->field('id,name')->where('company_id',$this->auth->company_id)->select();
        $type = [0 => ['id' => '0', 'name' => '全部'],1 => ['id' => '1', 'name' => '未结单'],2 => ['id' => '2', 'name' => '已完结']];
        $this->view->assign("typeList", $type);
    }

    public function index()
    {
        if ($this->request->isAjax()) {
            $model = new Materials;
            $year = $this->request->param('year', date('Y'));
            $category_id = $this->request->param('category_id', 0);
            $time = date("Y-m-d H:i:s");
            list($where, $sort, $order, $offset, $limit) = $this->buildparams(null);
            $total = $model
                ->where($where)
                ->where('company_id',$this->auth->company_id);
            if ($category_id>0) {
                $total = $total->where('materials_category_id', $category_id);
            }
            if ($year !== '') {
                $total = $total->whereTime('createtime', 'between', ["{$year}-1-1","{$year}-12-31"]);
            }
                
            $total = $total->where(function ($query) {
                	$query->where('find_in_set('.$this->user_id.',user_ids)')->whereor('find_in_set('.$this->group_id.',user_group_ids)');
            		})
                ->order($sort, $order)
                ->count();
            $list = $model
                ->where($where)
                ->where('company_id',$this->auth->company_id);
            if ($category_id>0) {
                $list = $list->where('materials_category_id', $category_id);
            }
            if ($year !== '') {
                $list = $list->whereTime('createtime', 'between', ["{$year}-1-1","{$year}-12-31"]);
            }    
            $list = $list->where(function ($query) {
                	$query->where('find_in_set('.$this->user_id.',user_ids)')->whereor('find_in_set('.$this->group_id.',user_group_ids)');
            		})
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();
            $result = array("total" => $total, "rows" => $list);

            return json($result);          
        }
        return $this->view->fetch('/index');
    }
    
    public function vcheck() //人工巡查
    {
        if ($this->request->isAjax()) {
            $model = new Materials;
            $group = $this->request->param('group', 'learning');
            $year = $this->request->param('year', date('Y'));
            $category_id = $this->request->param('category_id', 0);
            $time = date("Y-m-d H:i:s");
            list($where, $sort, $order, $offset, $limit) = $this->buildparams(null);
            $total = $model
                ->where($where)
                ->where('company_id',$this->auth->company_id);
            if ($category_id>0) {
                $total = $total->where('materials_category_id', $category_id);
            }
            if ($year !== '') {
                $total = $total->whereTime('createtime', 'between', ["{$year}-1-1","{$year}-12-31"]);
            }
                
            $total = $total->where(function ($query) {
                	$query->where('find_in_set('.$this->user_id.',user_ids)')->whereor('find_in_set('.$this->group_id.',user_group_ids)');
            		})
                ->order($sort, $order)
                ->count();
            $list = $model
                ->where($where)
                ->where('company_id',$this->auth->company_id);
            if ($category_id>0) {
                $list = $list->where('materials_category_id', $category_id);
            }
            if ($year !== '') {
                $list = $list->whereTime('createtime', 'between', ["{$year}-1-1","{$year}-12-31"]);
            }    
            $list = $list->where(function ($query) {
                	$query->where('find_in_set('.$this->user_id.',user_ids)')->whereor('find_in_set('.$this->group_id.',user_group_ids)');
            		})
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();
            $result = array("total" => $total, "rows" => $list);

            return json($result);
            
            
        }
        return $this->view->fetch('/vcheck');
    }
    
    public function scheck()//安全检查
    {
        if ($this->request->isAjax()) {
            $model = new Materials;
            $group = $this->request->param('group', 'learning');
            $year = $this->request->param('year', date('Y'));
            $category_id = $this->request->param('category_id', 0);
            $time = date("Y-m-d H:i:s");
            list($where, $sort, $order, $offset, $limit) = $this->buildparams(null);
            $total = $model
                ->where($where)
                ->where('company_id',$this->auth->company_id);
            if ($category_id==0) {
                $total = $total->where('main_status','in','1,2,3,4,5,9');
            }
            if ($category_id==1) {
                $total = $total->where('main_status','in','1,2,3,4');
            }
            if ($category_id==2) {
                $total = $total->where('main_status','in','5,9');
            }
            if ($year !== '') {
                $total = $total->whereTime('createtime', 'between', ["{$year}-1-1","{$year}-12-31"]);
            }
                
            $total = $total->where(function ($query) {
                	$query->where('find_in_set('.$this->user_id.',user_ids)')->whereor('find_in_set('.$this->group_id.',user_group_ids)');
            		})
                ->order($sort, $order)
                ->count();
            $list = $model
                ->where($where)
                ->where('company_id',$this->auth->company_id);
            if ($category_id>0) {
                $list = $list->where('materials_category_id', $category_id);
            }
            if ($year !== '') {
                $list = $list->whereTime('createtime', 'between', ["{$year}-1-1","{$year}-12-31"]);
            }    
            $list = $list->where(function ($query) {
                	$query->where('find_in_set('.$this->user_id.',user_ids)')->whereor('find_in_set('.$this->group_id.',user_group_ids)');
            		})
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();
            $result = array("total" => $total, "rows" => $list);

            return json($result);
            
            
        }
        return $this->view->fetch('/scheck');
    }
    
    public function viewtrouble()//查看隐患
    {
        if ($this->request->isAjax()) {
            $main = new MainModel;
            $year = $this->request->param('year', date('Y'));
            $category_id = $this->request->param('category_id', 0);
            $time = date("Y-m-d H:i:s");
            list($where, $sort, $order, $offset, $limit) = $this->buildparams(null);
            $total = $main
                ->where($where)
                ->where('company_id',$this->auth->company_id);
            if ($category_id==0) {
                $total = $total->where('main_status','in','1,2,3,4,5,9');
            }
            if ($category_id==1) {
                $total = $total->where('main_status','in','1,2,3,4');
            }
            if ($category_id==2) {
                $total = $total->where('main_status','in','5,9');
            }
            if ($year !== '') {
                $total = $total->whereTime('createtime', 'between', ["{$year}-1-1","{$year}-12-31"]);
            }
                
            $total = $total->where(function ($query) {
                	$query->where('find_in_set('.$this->user_id.',liabler)')->whereor('find_in_set('.$this->user_id.',processer)')->whereor('find_in_set('.$this->user_id.',checker)')->whereor('find_in_set('.$this->user_id.',insider)');
            		})
                ->order($sort, $order)
                ->count();
            $list = $main->alias('a')
            	 ->join('__TROUBLE_TYPE__ b', 'a.trouble_type_id = b.id')
            	 ->join('__TROUBLE_POINT__ c','a.point_id = c.id')
            	 ->field('a.*,b.trouble_type,c.point_code,c.point_name,c.point_address')
                ->where($where)
                ->where('a.company_id',$this->auth->company_id);
            if ($category_id==0) {
                $list = $list->where('a.main_status','in','1,2,3,4,5,9');
            }
            if ($category_id==1) {
                $list = $list->where('a.main_status','in','1,2,3,4');
            }
            if ($category_id==2) {
                $list = $list->where('a.main_status','in','5,9');
            }
            if ($year !== '') {
                $list = $list->whereTime('a.createtime', 'between', ["{$year}-1-1","{$year}-12-31"]);
            }    
            $list = $list->where(function ($query) {
                	$query->where('find_in_set('.$this->user_id.',a.liabler)')->whereor('find_in_set('.$this->user_id.',a.processer)')->whereor('find_in_set('.$this->user_id.',a.checker)')->whereor('find_in_set('.$this->user_id.',a.insider)');
            		})
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();
            $result = array("total" => $total, "rows" => $list);

            return json($result);          
        }
        return $this->view->fetch('/viewtrouble');
    }
    
    public function dispatch()//分派任务
    { 
        return $this->view->fetch('/dispatch');
    }
    public function solve() //处理隐患
    {
    	return $this->view->fetch('/solve');
    }
    public function roam() //任务流转
    {
    	return $this->view->fetch('/roam');
    }
    public function feedback() //隐患反馈
    {
    	return $this->view->fetch('/feedback');
    }
    public function review() //隐患复核
    {
    	return $this->view->fetch('/review');
    }
    
    public function getCount()
    {
            
            $main = new MainModel;
            $year = $this->request->param('year', date('Y'));
            $time = date("Y-m-d H:i:s");
            $count=[];
            $map = [];
            if ($year !== '') {
                $map['createtime']=['between time',["{$year}-1-1","{$year}-12-31"]];
            }
        		$type = [0 => ['id' => '0', 'name' => '全部'],1 => ['id' => '1', 'name' => '未结单'],2 => ['id' => '2', 'name' => '已完结']];
            foreach($type as $k=>$v){
            	if($v['id']==1) {
            	$count[$v['id']] = $main->where($map)->where(function ($query) {
                	$query->where('find_in_set('.$this->user_id.',liabler)')->whereor('find_in_set('.$this->user_id.',processer)')->whereor('find_in_set('.$this->user_id.',checker)')->whereor('find_in_set('.$this->user_id.',insider)');
            		})->where('main_status','in','1,2,3,4')->where('company_id',$this->auth->company_id)->count();
            	}
             	if($v['id']==2) {
            	$count[$v['id']] = $main->where($map)->where(function ($query) {
                	$query->where('find_in_set('.$this->user_id.',liabler)')->whereor('find_in_set('.$this->user_id.',processer)')->whereor('find_in_set('.$this->user_id.',checker)')->whereor('find_in_set('.$this->user_id.',insider)');
            		})->where('main_status','in','5,9')->where('company_id',$this->auth->company_id)->count();
            	}
            	if($v['id']==0) {
            	$count[$v['id']] = $main->where($map)->where(function ($query) {
                	$query->where('find_in_set('.$this->user_id.',liabler)')->whereor('find_in_set('.$this->user_id.',processer)')->whereor('find_in_set('.$this->user_id.',checker)')->whereor('find_in_set('.$this->user_id.',insider)');
            		})->where('main_status','in','1,2,3,4,5,9')->where('company_id',$this->auth->company_id)->count();
            	}
            }
            
            
            if ($this->request->isAjax()) {
                return json($count);
            }else{
                return $count;
            }
    }
    public function showtrouble()
    {
        $model = new MainModel;
       
        $id = $this->request->param('id');
        
        $trouble = $model->alias('a')
            	 ->join('__TROUBLE_TYPE__ b', 'a.trouble_type_id = b.id')
            	 ->join('__TROUBLE_POINT__ c','a.point_id = c.id')
            	 ->field('a.*,b.trouble_type,c.point_code,c.point_name,c.point_address')
            	 ->where(['a.id'=>$id,'a.company_id'=>$this->auth->company_id])
            	 ->find();

        $this->view->assign('row', $trouble);
       
        return $this->view->fetch('/showtrouble');
    }
    

    public function alert()
    {
        $params = $this->request->param();
        $this->view->assign('params', $params);
        return $this->view->fetch('/alert');
    }
}
