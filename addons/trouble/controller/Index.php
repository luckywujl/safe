<?php

namespace addons\trouble\controller;

use addons\trouble\model\Main as MainModel;
use addons\trouble\model\Log as LogModel;
use app\common\model\User;
use Think\Db;

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
        $type = [0 => ['id' => '0', 'name' => '全部'],1 => ['id' => '1', 'name' => '未结单'],2 => ['id' => '2', 'name' => '已完结']];
        $this->view->assign("typeList", $type);
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
        $type = [0 => ['id' => '0', 'name' => '全部'],1 => ['id' => '1', 'name' => '未结单'],2 => ['id' => '2', 'name' => '已完结']];
        $this->view->assign("typeList", $type);
        return $this->view->fetch('/viewtrouble');
    }
    
    public function dispatch()//分派任务
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
                $total = $total->where('main_status','in','1,2,3');
            }
            if ($category_id==1) {
                $total = $total->where('main_status','in','1');
            }
            if ($category_id==2) {
                $total = $total->where('main_status','in','2,3');
            }
            if ($year !== '') {
                $total = $total->whereTime('createtime', 'between', ["{$year}-1-1","{$year}-12-31"]);
            }
                
            $total = $total->where(function ($query) {
                	$query->where('find_in_set('.$this->user_id.',liabler)');//仅隐患负责人可见
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
                $list = $list->where('a.main_status','in','1,2,3');
            }
            if ($category_id==1) {
                $list = $list->where('a.main_status','in','1');
            }
            if ($category_id==2) {
                $list = $list->where('a.main_status','in','2,3');
            }
            if ($year !== '') {
                $list = $list->whereTime('a.createtime', 'between', ["{$year}-1-1","{$year}-12-31"]);
            }    
            $list = $list->where(function ($query) {
                	$query->where('find_in_set('.$this->user_id.',liabler)');//仅隐患负责人可见
            		})
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();
            $result = array("total" => $total, "rows" => $list);

            return json($result);          
        }
        $type = [0 => ['id' => '0', 'name' => '全部'],1 => ['id' => '1', 'name' => '未派单'],2 => ['id' => '2', 'name' => '已派单']];
        $this->view->assign("typeList", $type);
    
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
    
    public function getCount_dispatch()
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
                	$query->where('find_in_set('.$this->user_id.',liabler)');
            		})->where('main_status','in','1')->where('company_id',$this->auth->company_id)->count();
            	}
             	if($v['id']==2) {
            	$count[$v['id']] = $main->where($map)->where(function ($query) {
                	$query->where('find_in_set('.$this->user_id.',liabler)');
            		})->where('main_status','in','2,3')->where('company_id',$this->auth->company_id)->count();
            	}
            	if($v['id']==0) {
            	$count[$v['id']] = $main->where($map)->where(function ($query) {
                	$query->where('find_in_set('.$this->user_id.',liabler)');
            		})->where('main_status','in','1,2,3')->where('company_id',$this->auth->company_id)->count();
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
        $trouble['log'] = $this->getlog($id);//获取操作日志内容

        $trouble['main_text'] = $this->getstatus($trouble['main_status']);//将状态转换成文本
        $this->view->assign('row', $trouble);
       
        return $this->view->fetch('/showtrouble');
    }
    
    public function dispatchtrouble()
    {
        $model = new MainModel;
       
        $id = $this->request->param('id');
        
        $trouble = $model->alias('a')
            	 ->join('__TROUBLE_TYPE__ b', 'a.trouble_type_id = b.id')
            	 ->join('__TROUBLE_POINT__ c','a.point_id = c.id')
            	 ->field('a.*,b.trouble_type,c.point_code,c.point_name,c.point_address')
            	 ->where(['a.id'=>$id,'a.company_id'=>$this->auth->company_id])
            	 ->find();
        $trouble['log'] = $this->getlog($id);//获取操作日志内容
        $trouble['main_text'] = $this->getstatus($trouble['main_status']);//将状态转换成文本
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
        $this->view->assign('row', $trouble);
        return $this->view->fetch('/dispatchtrouble');
    }
    

    public function alert()
    {
        $params = $this->request->param();
        $this->view->assign('params', $params);
        return $this->view->fetch('/alert');
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
    
    public function getstatus($status)
    {
        if($status ==0){
        		$result= __("Main_status 0");
        }elseif($status ==1) {
        		$result= __("Main_status 1");
        }elseif($status ==2) {
        		$result= __("Main_status 2");
        }elseif($status ==3) {
				$result= __("Main_status 3");
        }elseif($status ==4) {
        		$result= __("Main_status 4");
        }elseif($status ==5) {
        		$result= __("Main_status 5");
        }else {
        		$result= __("Main_status 9");
        }
        return $result;
    }
}
