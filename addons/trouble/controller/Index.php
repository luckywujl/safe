<?php

namespace addons\trouble\controller;

use addons\trouble\model\Main as MainModel;
use addons\trouble\model\Course as CourseModel;
use addons\trouble\model\Record as RecordModel;
use addons\trouble\model\Result as ResultModel;
use app\admin\model\trouble\Info as Materials;
use app\common\model\User;

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
    protected $user_id = null;
    public function _initialize()
    {
        parent::_initialize();
        $this->group_id = User::where('id', $this->auth->id)->value('group_id');
        $this->user_id = $this->auth->id;
        $this->company_id = $this->auth->company_id;
        $materials_type = new \app\admin\model\materials\Category;
        $type = $materials_type->field('id,name')->where('company_id',$this->auth->company_id)->select();
        $type_a = [];
        $type_a['id']=0;
        $type_a['name']= '全部';
        $type[] = $type_a;
        $this->view->assign("typeList", $type);  
    }

    public function index()
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
        return $this->view->fetch('/scheck');
    }
    
    public function viewtrouble()//查看隐患
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
            
            $model = new Materials;
            $year = $this->request->param('year', date('Y'));
            
            $time = date("Y-m-d H:i:s");
            $count=[];
            $map = [];
            if ($year !== '') {
                $map['createtime']=['between time',["{$year}-1-1","{$year}-12-31"]];
            }
            
            $materials_type = new \app\admin\model\materials\Category;
            $type = $materials_type->field('id,name')->where('company_id',$this->auth->company_id)->select();
        		$type_a = [];
        		$type_a['id']=0;
        		$type_a['name']= '全部';
        		$type[] = $type_a;
            foreach($type as $k=>$v){
            	if($v['id']>0) {
            	$count[$v['id']] = $model->where($map)->where(function ($query) {
                	$query->where('find_in_set('.$this->user_id.',user_ids)')->whereor('find_in_set('.$this->group_id.',user_group_ids)');
            		})->where('materials_category_id',$v['id'])->where('company_id',$this->auth->company_id)->count();
            }else {
            	$count[$v['id']] = $model->where($map)->where(function ($query) {
                	$query->where('find_in_set('.$this->user_id.',user_ids)')->whereor('find_in_set('.$this->group_id.',user_group_ids)');
            		})->where('company_id',$this->auth->company_id)->count();
            }
            }
            
            
            if ($this->request->isAjax()) {
                return json($count);
            }else{
                return $count;
            }
    }
    public function main()
    {
        $model = new CourseModel;
        $id = $this->request->param('id');
        if (!$id) {
            $this->redirect(addon_url("/training/Index/alert", ['msg'=>'未获取到课程']));
        }
        $main = MainModel::get($id);
        if (!$main) {
            $this->redirect(addon_url("/training/Index/alert", ['msg'=>'培训尚未安排课程，请等待管理员设置']));
        }
        $list = $model->with(['record'=>function ($query) use ($id) {
            $query->where('user_id', $this->auth->id)->where('training_main_id', $id);
        }])->where('status', 'normal')->where('id', 'in', $main['training_course_ids'])->where('deletetime', 'null')->order('weigh DES')->select();
        $this->view->assign('list', $list);
        $this->view->assign('main', $main);
        return $this->view->fetch('/main');
    }

    public function alert()
    {
        $params = $this->request->param();
        $this->view->assign('params', $params);
        return $this->view->fetch('/alert');
    }
}
