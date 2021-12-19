<?php

namespace addons\trouble\controller;

use addons\trouble\model\Main as MainModel;
use addons\trouble\model\Log as LogModel;
use addons\trouble\model\Point as PointModel;
use addons\trouble\model\Type as TypeModel;
use app\common\model\User as UserModel;
use Think\Db;
use fast\Tree;
use think\Validate;
use addons\trouble\controller\Jssdk;

//use app\common\model\User;

class Index extends Base
{

    /**
     * 无需登录的方法,同时也就不需要鉴权了
     * @var array
     */
    protected $noNeedLogin = ['report'];

    /**
     * 无需鉴权的方法,但需要登录
     * @var array
     */
    protected $noNeedRight = ['*'];
    protected $relationSearch = true;
    protected $group_id = null;
    protected $department_id = null;
    protected $user_id = null;
    protected $model = null;
    
    protected $dataLimit = 'personal';
    
    protected $dataLimitField = 'company_id';
    
    public function _initialize()
    {
        parent::_initialize();
        
        //$this->group_id = User::where('id', $this->auth->id)->value('group_id');
        $this->user_id = $this->auth->id;
        $this->company_id = $this->auth->company_id;
        
       // $type = $materials_type->field('id,name')->where('company_id',$this->auth->company_id)->select();
        
    }

    
    public function vcheck() //人工巡查
    {
        if ($this->request->isAjax()) {
            return $this->getdata('1,2,3,4,5,6,7,9','1,2,3,4,5,6','7,9',0);
      	}
        $type = [0 => ['id' => '0', 'name' => '全部'],1 => ['id' => '1', 'name' => '进行中'],2 => ['id' => '2', 'name' => '已完结']];
        $this->view->assign("typeList", $type);
        return $this->view->fetch('/vcheck');
    }
        
    
    public function scheck()//安全检查
    {
        if ($this->request->isAjax()) {
            return $this->getdata('1,2,3,4,5,6,7,9','1,2,3,4,5,6','7,9',1);
      	}
        $type = [0 => ['id' => '0', 'name' => '全部'],1 => ['id' => '1', 'name' => '进行中'],2 => ['id' => '2', 'name' => '已完结']];
        $this->view->assign("typeList", $type);
        return $this->view->fetch('/scheck');
    }
    
    public function view()//查看隐患
    {
        if ($this->request->isAjax()) {
            return $this->getdata('1,2,3,4,5,6,7,9','1,2,3,4,5,6','7,9',2);
      }
        $type = [0 => ['id' => '0', 'name' => '全部'],1 => ['id' => '1', 'name' => '进行中'],2 => ['id' => '2', 'name' => '已完结']];
        $this->view->assign("typeList", $type);
        return $this->view->fetch('/view');
    }
    
    public function dispatch()//分派任务
    { 
    	 if ($this->request->isAjax()) {
            return $this->getdata('1,2,4','1,4','2',3);
      }
        $type = [0 => ['id' => '0', 'name' => '全部'],1 => ['id' => '1', 'name' => '待派单'],2 => ['id' => '2', 'name' => '已派单']];
        $this->view->assign("typeList", $type);
    
        return $this->view->fetch('/dispatch');
    }
    
    public function solve() //处理隐患
    {
    	if ($this->request->isAjax()) {
            return $this->getdata('2,3,5','2,3','5',4);
      }
        $type = [0 => ['id' => '0', 'name' => '全部'],1 => ['id' => '1', 'name' => '待处理'],2 => ['id' => '2', 'name' => '已处理']];
        $this->view->assign("typeList", $type);
    	return $this->view->fetch('/solve');
    } 
     
    public function roam() //任务流转
    {
    	if ($this->request->isAjax()) {
            return $this->getdata('2,3,4','2,3','4',5);
      }
        $type = [0 => ['id' => '0', 'name' => '全部'],1 => ['id' => '1', 'name' => '待流转'],2 => ['id' => '2', 'name' => '已流转']];
        $this->view->assign("typeList", $type);
    	return $this->view->fetch('/roam');
    } 
    
    public function review() //隐患复核
    {
    	if ($this->request->isAjax()) {
            return $this->getdata('5,6','5','6',6);
      }
        $type = [0 => ['id' => '0', 'name' => '全部'],1 => ['id' => '1', 'name' => '待复核'],2 => ['id' => '2', 'name' => '已复核']];
        $this->view->assign("typeList", $type);
    	return $this->view->fetch('/review');
    }
    
    public function feedback() //隐患反馈
    {
    	if ($this->request->isAjax()) {
            return $this->getdata('6,7','6','7',7);
      }
        $type = [0 => ['id' => '0', 'name' => '全部'],1 => ['id' => '1', 'name' => '待反馈'],2 => ['id' => '2', 'name' => '已反馈']];
        $this->view->assign("typeList", $type);
    	return $this->view->fetch('/feedback');
    }
    
    public function getCount()
    {
            
            $main = new MainModel;
            $year = $this->request->param('year', date('Y'));
            $all = $this->request->param('all');
            $no = $this->request->param('no');
            $already = $this->request->param('already');
            $operator = $this->request->param('operator');
            $time = date("Y-m-d H:i:s");
            $count=[];
            $map = [];
            if ($year !== '') {
                $map['createtime']=['between time',["{$year}-1-1","{$year}-12-31"]];
            }
        		$type = [0 => ['id' => '0', 'name' => '全部'],1 => ['id' => '1', 'name' => '未结单'],2 => ['id' => '2', 'name' => '已完结']];
            if($operator==0) { //查看人工巡检
            	foreach($type as $k=>$v){
            		if($v['id']==0) {//全部
            		$count[$v['id']] = $main->where($map)->where(function ($query) {
                		$query->where('find_in_set('.$this->user_id.',informer)');
            			})->where('main_status','in',$all)->where('company_id',$this->auth->company_id)->where('source_type',1)->count();
            		}
            		if($v['id']==1) {//未
            		$count[$v['id']] = $main->where($map)->where(function ($query) {
                		$query->where('find_in_set('.$this->user_id.',informer)');
            			})->where('main_status','in',$no)->where('company_id',$this->auth->company_id)->where('source_type',1)->count();
            		}
             		if($v['id']==2) {//已
            		$count[$v['id']] = $main->where($map)->where(function ($query) {
                		$query->where('find_in_set('.$this->user_id.',informer)');
            			})->where('main_status','in',$already)->where('company_id',$this->auth->company_id)->where('source_type',1)->count();
            		}
            		
            	}
         	}
         	if($operator==1) { //查看安全检查
            	foreach($type as $k=>$v){
            		if($v['id']==0) {//全部
            		$count[$v['id']] = $main->where($map)->where(function ($query) {
                		$query->where('find_in_set('.$this->user_id.',informer)');
            			})->where('main_status','in',$all)->where('company_id',$this->auth->company_id)->where('source_type',2)->count();
            		}
            		if($v['id']==1) {//未
            		$count[$v['id']] = $main->where($map)->where(function ($query) {
                		$query->where('find_in_set('.$this->user_id.',informer)');
            			})->where('main_status','in',$no)->where('company_id',$this->auth->company_id)->where('source_type',2)->count();
            		}
             		if($v['id']==2) {//已
            		$count[$v['id']] = $main->where($map)->where(function ($query) {
                		$query->where('find_in_set('.$this->user_id.',informer)');
            			})->where('main_status','in',$already)->where('company_id',$this->auth->company_id)->where('source_type',2)->count();
            		}
            		
            	}
         	}
         	if($operator==2) { //查看隐患
            	foreach($type as $k=>$v){
            		if($v['id']==0) {//全部
            		$count[$v['id']] = $main->where($map)->where(function ($query) {
                		$query->where('find_in_set('.$this->user_id.',liabler)')->whereor('find_in_set('.$this->user_id.',processer)')->whereor('find_in_set('.$this->user_id.',checker)')->whereor('find_in_set('.$this->user_id.',insider)');
            			})->where('main_status','in',$all)->where('company_id',$this->auth->company_id)->count();
            		}
            		if($v['id']==1) {//未
            		$count[$v['id']] = $main->where($map)->where(function ($query) {
                		$query->where('find_in_set('.$this->user_id.',liabler)')->whereor('find_in_set('.$this->user_id.',processer)')->whereor('find_in_set('.$this->user_id.',checker)')->whereor('find_in_set('.$this->user_id.',insider)');
            			})->where('main_status','in',$no)->where('company_id',$this->auth->company_id)->count();
            		}
             		if($v['id']==2) {//已
            		$count[$v['id']] = $main->where($map)->where(function ($query) {
                		$query->where('find_in_set('.$this->user_id.',liabler)')->whereor('find_in_set('.$this->user_id.',processer)')->whereor('find_in_set('.$this->user_id.',checker)')->whereor('find_in_set('.$this->user_id.',insider)');
            			})->where('main_status','in',$already)->where('company_id',$this->auth->company_id)->count();
            		}
            		
            	}
         	}
         	if($operator==3) { //任务分派
            	foreach($type as $k=>$v){
            		if($v['id']==0) {//全部
            		$count[$v['id']] = $main->where($map)->where(function ($query) {
                		$query->where('find_in_set('.$this->user_id.',liabler)');
            			})->where('main_status','in',$all)->where('company_id',$this->auth->company_id)->count();
            		}
            		if($v['id']==1) {//未
            		$count[$v['id']] = $main->where($map)->where(function ($query) {
                		$query->where('find_in_set('.$this->user_id.',liabler)');
            			})->where('main_status','in',$no)->where('company_id',$this->auth->company_id)->count();
            		}
             		if($v['id']==2) {//已
            		$count[$v['id']] = $main->where($map)->where(function ($query) {
                		$query->where('find_in_set('.$this->user_id.',liabler)');
            			})->where('main_status','in',$already)->where('company_id',$this->auth->company_id)->count();
            		}
            		
            	}
         	}
         	if($operator==4) { //任务处理
            	foreach($type as $k=>$v){
            		if($v['id']==0) {//全部
            		$count[$v['id']] = $main->where($map)->where(function ($query) {
                		$query->where('find_in_set('.$this->user_id.',processer)');
            			})->where('main_status','in',$all)->where('company_id',$this->auth->company_id)->count();
            		}
            		if($v['id']==1) {//未
            		$count[$v['id']] = $main->where($map)->where(function ($query) {
                		$query->where('find_in_set('.$this->user_id.',processer)');
            			})->where('main_status','in',$no)->where('company_id',$this->auth->company_id)->count();
            		}
             		if($v['id']==2) {//已
            		$count[$v['id']] = $main->where($map)->where(function ($query) {
                		$query->where('find_in_set('.$this->user_id.',processer)');
            			})->where('main_status','in',$already)->where('company_id',$this->auth->company_id)->count();
            		}
            		
            	}
         	}
         	if($operator==5) { //任务流转
            	foreach($type as $k=>$v){
            		if($v['id']==0) {//全部
            		$count[$v['id']] = $main->where($map)->where(function ($query) {
                		$query->where('find_in_set('.$this->user_id.',processer)');
            			})->where('main_status','in',$all)->where('company_id',$this->auth->company_id)->count();
            		}
            		if($v['id']==1) {//未
            		$count[$v['id']] = $main->where($map)->where(function ($query) {
                		$query->where('find_in_set('.$this->user_id.',processer)');
            			})->where('main_status','in',$no)->where('company_id',$this->auth->company_id)->count();
            		}
             		if($v['id']==2) {//已
            		$count[$v['id']] = $main->where($map)->where(function ($query) {
                		$query->where('find_in_set('.$this->user_id.',processer)');
            			})->where('main_status','in',$already)->where('company_id',$this->auth->company_id)->count();
            		}
            		
            	}
         	}
         	if($operator==6) { //任务复核
            	foreach($type as $k=>$v){
            		if($v['id']==0) {//全部
            		$count[$v['id']] = $main->where($map)->where(function ($query) {
                		$query->where('find_in_set('.$this->user_id.',checker)');
            			})->where('main_status','in',$all)->where('company_id',$this->auth->company_id)->count();
            		}
            		if($v['id']==1) {//未
            		$count[$v['id']] = $main->where($map)->where(function ($query) {
                		$query->where('find_in_set('.$this->user_id.',checker)');
            			})->where('main_status','in',$no)->where('company_id',$this->auth->company_id)->count();
            		}
             		if($v['id']==2) {//已
            		$count[$v['id']] = $main->where($map)->where(function ($query) {
                		$query->where('find_in_set('.$this->user_id.',checker)');
            			})->where('main_status','in',$already)->where('company_id',$this->auth->company_id)->count();
            		}
            		
            	}
         	}
         	if($operator==7) { //任务反馈
            	foreach($type as $k=>$v){
            		if($v['id']==0) {//全部
            		$count[$v['id']] = $main->where($map)->where(function ($query) {
                		$query->where('find_in_set('.$this->user_id.',liabler)');
            			})->where('main_status','in',$all)->where('company_id',$this->auth->company_id)->count();
            		}
            		if($v['id']==1) {//未
            		$count[$v['id']] = $main->where($map)->where(function ($query) {
                		$query->where('find_in_set('.$this->user_id.',liabler)');
            			})->where('main_status','in',$no)->where('company_id',$this->auth->company_id)->count();
            		}
             		if($v['id']==2) {//已
            		$count[$v['id']] = $main->where($map)->where(function ($query) {
                		$query->where('find_in_set('.$this->user_id.',liabler)');
            			})->where('main_status','in',$already)->where('company_id',$this->auth->company_id)->count();
            		}
            		
            	}
         	}
            
            
            if ($this->request->isAjax()) {
                return json($count);
            }else{
                return $count;
            }
    }
    
    
    public function showtrouble() //查看隐患详情
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
       
        return $this->view->fetch('/child/showtrouble');
    }
    
    public function dispatchtrouble() //任务分派详情
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
        		 //$params = $this->request->param();//接收过滤条件	
             $params = $this->request->post('row/a');
            if ($params) {
                $trouble_info = $model->where(['company_id'=>$this->auth->company_id,'id'=>$params['id']])->find();	
                $data['id'] = $params['id'];
                $data['processer'] = $params['processer'];
                $data['checker'] = $params['checker'];
                if($params['processer']!=='') {
                	$data['main_status'] = 2;
                }
                if($params['word']!=='') {
                	$data['remark'] = $params['remark']."\n"."派单留言：".$params['word'].'('.$this->auth->nickname.')';
                }          
                $result = false;
                Db::startTrans();
                try {
                    $result = $trouble_info->save($data);
                    if($trouble['main_status']==1) {
                    		LogModel::create(['main_id'=>$params['id'],'log_time'=>time(),'log_operator'=>$this->auth->nickname,'log_content'=>'完成派单，等待处理...','company_id'=>$this->auth->company_id]);
                	  }else {
                 			LogModel::create(['main_id'=>$params['id'],'log_time'=>time(),'log_operator'=>$this->auth->nickname,'log_content'=>'修改了派单信息，等待处理...','company_id'=>$this->auth->company_id]);
                 	 }
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
                    $this->success('派单成功');
                } else {
                    $this->error(__('No rows were updated'));
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        $this->view->assign('row', $trouble);
        return $this->view->fetch('/child/dispatchtrouble');
    }
    
    public function solvetrouble() //任务处理详情
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
        		 //$params = $this->request->param();//接收过滤条件	
             $params = $this->request->post('row/a');
            if ($params) {
                $trouble_info = $model->where(['company_id'=>$this->auth->company_id,'id'=>$params['id']])->find();	
                $data['id'] = $params['id'];
                $data['process_pic'] =$params['process_pic'];
                $data['main_status'] = 5;//完成任务，等待复核
                if($params['word']!=='') {
                	$data['remark'] = $params['remark']."\n"."处理留言：".$params['word'].'('.$this->auth->nickname.')';
                }          
                $result = false;
                Db::startTrans();
                try {
                    $result = $trouble_info->save($data);
                    if($trouble['main_status']==5) {
                    		LogModel::create(['main_id'=>$params['id'],'log_time'=>time(),'log_operator'=>$this->auth->nickname,'log_content'=>'重新排除隐患，等待复核...','company_id'=>$this->auth->company_id]);
                	  }else {
                 			LogModel::create(['main_id'=>$params['id'],'log_time'=>time(),'log_operator'=>$this->auth->nickname,'log_content'=>'排除隐患，等待复核...','company_id'=>$this->auth->company_id]);
                 	 }
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
                    $this->success('隐患排除!');
                } else {
                    $this->error(__('No rows were updated'));
                }
            }
            
            $this->error(__('Parameter %s can not be empty', ''));
        }
        //加入微信API调用
        //$jssdk = new Jssdk("wx4f79233878b9f770", "10eb3f75adafacbaa3c584908395c982");
        //$signPackage = $jssdk->GetSignPackage();
        //$this->view->assign('signPackage',$signPackage);
        $this->view->assign('row', $trouble);
        return $this->view->fetch('/child/solvetrouble');
    }
    
    public function reviewtrouble() //复核处理详情
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
        		 //$params = $this->request->param();//接收过滤条件	
             $params = $this->request->post('row/a');
            if ($params) {
                $trouble_info = $model->where(['company_id'=>$this->auth->company_id,'id'=>$params['id']])->find();	
                $data['id'] = $params['id'];
                $data['finish_pic'] =$params['finish_pic'];
                if($params['type']=='0') {
                		$data['main_status'] = 6;//完成任务，等待复核
             		}else {
             			$data['main_status'] = 3;//重新处理
             		}
                if($params['word']!=='') {
                	$data['remark'] = $params['remark']."\n"."复核留言：".$params['word'].'('.$this->auth->nickname.')';
                }          
                $result = false;
                Db::startTrans();
                try {
                    $result = $trouble_info->save($data);
                    if($params['type']=='0') {
                    	$msg = '隐患排除情况已复核通过!';
                    if($trouble['main_status']==5) {
                    		LogModel::create(['main_id'=>$params['id'],'log_time'=>time(),'log_operator'=>$this->auth->nickname,'log_content'=>'复核隐患排除情况通过，等待反馈结单...','company_id'=>$this->auth->company_id]);
                	  }else {
                 			LogModel::create(['main_id'=>$params['id'],'log_time'=>time(),'log_operator'=>$this->auth->nickname,'log_content'=>'重新复核隐患排除情况通过，等待反馈结单...','company_id'=>$this->auth->company_id]);
                 	 }
                 }else {
                 		$msg = '隐患排除情况已复核未通过!';
                 		LogModel::create(['main_id'=>$params['id'],'log_time'=>time(),'log_operator'=>$this->auth->nickname,'log_content'=>'复核隐患排除情况未通过，等待重新处理...','company_id'=>$this->auth->company_id]);
                 }
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
                    $this->success($msg);
                } else {
                    $this->error(__('No rows were updated'));
                }
            }
            
            $this->error(__('Parameter %s can not be empty', ''));
        }
        
        $this->view->assign('row', $trouble);
        return $this->view->fetch('/child/reviewtrouble');
    }
    public function roamtrouble() //流转任务
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
        		 //$params = $this->request->param();//接收过滤条件	
             $params = $this->request->post('row/a');
            if ($params) {
                $trouble_info = $model->where(['company_id'=>$this->auth->company_id,'id'=>$params['id']])->find();	
                $data['id'] = $params['id'];
             	 $data['main_status'] = 4;//进入流转状态，等待重新派单
                if($params['word']!=='') {
                	$data['remark'] = $params['remark']."\n"."流转留言：".$params['word'].'('.$this->auth->nickname.')';
                }          
                $result = false;
                Db::startTrans();
                try {
                    $result = $trouble_info->save($data);     
                    	$msg = '隐患排除任务已流转!';
                    	LogModel::create(['main_id'=>$params['id'],'log_time'=>time(),'log_operator'=>$this->auth->nickname,'log_content'=>'隐患排除任务已流转，等待重新派单...','company_id'=>$this->auth->company_id]); 
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
                    $this->success($msg);
                } else {
                    $this->error(__('No rows were updated'));
                }
            }
            
            $this->error(__('Parameter %s can not be empty', ''));
        }
        
        $this->view->assign('row', $trouble);
        return $this->view->fetch('/child/roamtrouble');
    }
    
    public function feedbacktrouble() //反馈隐患
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
        		 //$params = $this->request->param();//接收过滤条件	
             $params = $this->request->post('row/a');
             if ($params) {
                $trouble_info = $model->where(['company_id'=>$this->auth->company_id,'id'=>$params['id']])->find();	
                $data['id'] = $params['id'];
                $data['main_status'] = 7;//完成任务，等待复核	
                if($params['word']!=='') {
                	$data['remark'] = $params['remark']."\n"."反馈留言：".$params['word'].'('.$this->auth->nickname.')';
                }          
                $result = false;
                Db::startTrans();
                try {
                    $result = $trouble_info->save($data);
                    if($trouble['main_status']==6) {
                    		LogModel::create(['main_id'=>$params['id'],'log_time'=>time(),'log_operator'=>$this->auth->nickname,'log_content'=>'隐患处理已完结，任务完成！','company_id'=>$this->auth->company_id]);
                    		$msg = '隐患反馈完成，任务结单!';
                	  }
                	  
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
                    $this->success($msg);
                } else {
                    $this->error(__('操作失败'));
                }
            }
            
            $this->error(__('Parameter %s can not be empty', ''));
        }
        
        $this->view->assign('row', $trouble);
        return $this->view->fetch('/child/feedbacktrouble');
    }
    
    public function report() //扫码报警
    {	
    	 $point_id = $this->request->param('point_id');
    	 if(isset($point_id)) {
    	 	$point_info = new PointModel;
    	 	$model = new MainModel;
    	 	$point = $point_info->where('id',$point_id)->find();
    	 	if($point) {
       		if ($this->user_id) {
       			$point['type'] = '1';  //自己人
       		} else {
       			$point['type'] = '0';	//路人
       		}	
       	} 
       if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if ($params) {
                $params['main_status'] = 0; //草稿状态
                if($this->user_id) {  //如果有用户信息，则视为自己人
                	$params['informer'] = $this->user_id;
                	$params['informer_name'] = $this->auth->jobnumber.'-'.$this->auth->nickname.'('.$this->auth->mobile.')';	
                } else {
                	if($params['informer_name']=='') {
                		$params['informer_name'] = '匿名';
                	}	  
                }
                //加入信息编码生成代码
                $main = $model
                ->where('createtime','between time',[date('Y-m-01 00:00:01'),date('Y-m-31 23:59:59')])
                ->where(['company_id'=>$point['company_id']]) //出库单
            	 -> order('main_code','desc')->limit(1)->select();
        	       if (count($main)>0) {
        	       $item = $main[0];
        	  	    $code = '0000'.(substr($item['main_code'],8,4)+1);
        	  	    $code = substr($code,strlen($code)-4,4);
        	      	$params['main_code'] = 'YH'.date('Ym').$code;
        	      	} else {
        	  	   	$params['main_code']='YH'.date('Ym').'0001';
        	      	}
        	       $params['company_id'] = $point['company_id'];
                //完成信息编码生成
                $result = false;
                Db::startTrans();
                try {
                    $result = $model->allowField(true)->save($params);
                    $id = $model->id;
                    LogModel::create(['main_id'=>$id,'log_time'=>time(),'log_operator'=>$this->auth->nickname,'log_content'=>'提交报警信息，等待接警处理...','company_id'=>$this->auth->company_id]);
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
                    $this->success('报警完成！谢谢您的支持');
                } else {
                    $this->error(__('No rows were inserted'));
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        $this->view->assign('row', $point);
        return $this->view->fetch('/report');	
    	}
    	 
    }
    
    public function selectoperator() //选择人员
    {
        $model = new UserModel;
        $department_id = $this->auth->department_id;
        if ($this->request->isAjax()) {
        		//如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
            	return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams(null);
            $total = $model
            	 ->where(['department_id'=>$department_id,'company_id'=>$this->auth->company_id])
                ->order($sort, $order)
                ->count();
            
            $list = $model
            	 ->where(['department_id'=>$department_id,'company_id'=>$this->auth->company_id])
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();
            $result = array("total" => $total, "rows" => $list);
            return json($result);          
        }    
        return $this->view->fetch('/child/selectoperator');
    }

    public function selecttroubletype() //选择隐患类型
    {
        $model = new TypeModel;
        
        if ($this->request->isAjax()) {
        		//如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
            	return $this->selecttype();
            }
            
        }    
        //return $this->view->fetch('/child/selectoperator');
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
        }elseif($status ==6) {
        		$result= __("Main_status 6");
        }elseif($status ==7) {
        		$result= __("Main_status 7");
        }else {
        		$result= __("Main_status 9");
        }
        return $result;
    }
    
    public function getdata($all,$no,$already,$operator) //获取数据
    {
    	
            $main = new MainModel;
            //$operator = 'processer';
            $year = $this->request->param('year', date('Y'));
            $category_id = $this->request->param('category_id', 0);
            $time = date("Y-m-d H:i:s");
            list($where, $sort, $order, $offset, $limit) = $this->buildparams(null);
            $total = $main
                ->where($where)
                ->where('company_id',$this->auth->company_id);
            if ($category_id==0) {
                $total = $total->where('main_status','in',$all);
            }
            if ($category_id==1) {
                $total = $total->where('main_status','in',$no);
            }
            if ($category_id==2) {
                $total = $total->where('main_status','in',$already);
            }
            if ($year !== '') {
                $total = $total->whereTime('createtime', 'between', ["{$year}-1-1","{$year}-12-31"]);
            }
            if($operator==0) {    
            	$total = $total->where('informer',$this->user_id)->where('source_type',1);
            }
            if($operator==1) {    
            	$total = $total->where('informer',$this->user_id)->where('source_type',2);
            }
            if($operator==2) {    
            	$total = $total->where(function ($query) {
                	$query->where('find_in_set('.$this->user_id.',liabler)')->whereor('find_in_set('.$this->user_id.',processer)')->whereor('find_in_set('.$this->user_id.',checker)')->whereor('find_in_set('.$this->user_id.',insider)');
            		});
            }
            if($operator==3) {    
            	$total = $total->where(function ($query) {
                	$query->where('find_in_set('.$this->user_id.',liabler)');//仅隐患负责人可见
            		});
            }
            if($operator==4) {    
            	$total = $total->where(function ($query) {
                	$query->where('find_in_set('.$this->user_id.',processer)');//仅隐患负责人可见
            		});
            }
            if($operator==5) {    
            	$total = $total->where(function ($query) {
                	$query->where('find_in_set('.$this->user_id.',processer)');//仅隐患负责人可见
            		});
            }
            if($operator==6) {    
            	$total = $total->where(function ($query) {
                	$query->where('find_in_set('.$this->user_id.',checker)');//仅隐患负责人可见
            		});
            }
            if($operator==7) {    
            	$total = $total->where(function ($query) {
                	$query->where('find_in_set('.$this->user_id.',liabler)');//仅隐患负责人可见
            		});
            }
             $total =  $total->order($sort, $order)
                ->count();
            $list = $main->alias('a')
            	 ->join('__TROUBLE_TYPE__ b', 'a.trouble_type_id = b.id')
            	 ->join('__TROUBLE_POINT__ c','a.point_id = c.id')
            	 ->field('a.*,b.trouble_type,c.point_code,c.point_name,c.point_address')
                ->where($where)
                ->where('a.company_id',$this->auth->company_id);
            if ($category_id==0) {
                $list = $list->where('a.main_status','in',$all);
            }
            if ($category_id==1) {
                $list = $list->where('a.main_status','in',$no);
            }
            if ($category_id==2) {
                $list = $list->where('a.main_status','in',$already);
            }
            if ($year !== '') {
                $list = $list->whereTime('a.createtime', 'between', ["{$year}-1-1","{$year}-12-31"]);
            }
            if($operator==0) {    
            	$list = $list->where('informer',$this->user_id)->where('source_type',1);
            }
            if($operator==1) {    
            	$list = $list->where('informer',$this->user_id)->where('source_type',2);
            }
            if($operator==2) {    
            	$list = $list->where(function ($query) {
                	$query->where('find_in_set('.$this->user_id.',liabler)')->whereor('find_in_set('.$this->user_id.',processer)')->whereor('find_in_set('.$this->user_id.',checker)')->whereor('find_in_set('.$this->user_id.',insider)');
            		});
            }
            if($operator==3) {    
            	$list = $list->where(function ($query) {
                	$query->where('find_in_set('.$this->user_id.',liabler)');//仅隐患负责人可见
            		});
            }
            if($operator==4) {    
            	$list = $list->where(function ($query) {
                	$query->where('find_in_set('.$this->user_id.',processer)');//仅处置人可见
            		});
            }   
            if($operator==5) {    
            	$list = $list->where(function ($query) {
                	$query->where('find_in_set('.$this->user_id.',processer)');//仅处置人可见
            		});
            } 
            if($operator==6) {    
            	$list = $list->where(function ($query) {
                	$query->where('find_in_set('.$this->user_id.',checker)');//仅处置人可见
            		});
            }
            if($operator==7) {    
            	$list = $list->where(function ($query) {
                	$query->where('find_in_set('.$this->user_id.',liabler)');//仅处置人可见
            		});
            }
            
            $list = $list->order($sort, $order)
                ->limit($offset, $limit)
                ->select();
            foreach($list as $k=>$v){
            	$v['status'] = $this->getstatus($v['main_status']);
            }
            $result = array("total" => $total, "rows" => $list);
            return json($result);            
    }
    
    /**
     * Selectpage的实现方法
     *
     * 当前方法只是一个比较通用的搜索匹配,请按需重载此方法来编写自己的搜索逻辑,$where按自己的需求写即可
     * 这里示例了所有的参数，所以比较复杂，实现上自己实现只需简单的几行即可
     *
     */
    protected function selectpage()
    {
        //设置过滤方法
        $this->request->filter(['trim', 'strip_tags', 'htmlspecialchars']);

        //搜索关键词,客户端输入以空格分开,这里接收为数组
        $word = (array)$this->request->request("q_word/a");
        //当前页
        $page = $this->request->request("pageNumber");
        //分页大小
        $pagesize = $this->request->request("pageSize");
        //搜索条件
        $andor = $this->request->request("andOr", "and", "strtoupper");
        //排序方式
        $orderby = (array)$this->request->request("orderBy/a");
        //显示的字段
        $field = $this->request->request("showField");
        //主键
        $primarykey = $this->request->request("keyField");
        //主键值
        $primaryvalue = $this->request->request("keyValue");
        //搜索字段
        $searchfield = (array)$this->request->request("searchField/a");
        //自定义搜索条件
        $custom = (array)$this->request->request("custom/a");
        //是否返回树形结构
        $istree = $this->request->request("isTree", 0);
        $ishtml = $this->request->request("isHtml", 0);
        if ($istree) {
            $word = [];
            $pagesize = 999999;
        }
        $order = [];
        foreach ($orderby as $k => $v) {
            $order[$v[0]] = $v[1];
        }
        $field = $field ? $field : 'name';
        
        //如果有primaryvalue,说明当前是初始化传值
        if ($primaryvalue !== null) {
            $where = [$primarykey => ['in', $primaryvalue]];
            $pagesize = 999999;
        } else {
            $where = function ($query) use ($word, $andor, $field, $searchfield, $custom) {
                $logic = $andor == 'AND' ? '&' : '|';
                $searchfield = is_array($searchfield) ? implode($logic, $searchfield) : $searchfield;
                $searchfield = str_replace(',', $logic, $searchfield);
                $word = array_filter(array_unique($word));
                if (count($word) == 1) {
                    $query->where($searchfield, "like", "%" . reset($word) . "%");
                } else {
                    $query->where(function ($query) use ($word, $searchfield) {
                        foreach ($word as $index => $item) {
                            $query->whereOr(function ($query) use ($item, $searchfield) {
                                $query->where($searchfield, "like", "%{$item}%");
                            });
                        }
                    });
                }
                if ($custom && is_array($custom)) {
                    foreach ($custom as $k => $v) {
                        if (is_array($v) && 2 == count($v)) {
                            $query->where($k, trim($v[0]), $v[1]);
                        } else {
                            $query->where($k, '=', $v);
                        }
                    }
                }
            };
        }
        $model = new UserModel;
        $total = $model->where($where)->count();
        if ($total > 0) {
           
            //如果有primaryvalue,说明当前是初始化传值,按照选择顺序排序
            if ($primaryvalue !== null && preg_match("/^[a-z0-9_\-]+$/i", $primarykey)) {
                $primaryvalue = array_unique(is_array($primaryvalue) ? $primaryvalue : explode(',', $primaryvalue));
                //修复自定义data-primary-key为字符串内容时，给排序字段添加上引号
                $primaryvalue = array_map(function ($value) {
                    return '\'' . $value . '\'';
                }, $primaryvalue);

                $primaryvalue = implode(',', $primaryvalue);

                $model->orderRaw("FIELD(`{$primarykey}`, {$primaryvalue})");
            } else {
                $model->order($order);
            }
            
            $datalist = $model->where($where)
            	 ->where(['department_id'=>$this->auth->department_id,'company_id'=>$this->auth->company_id])
                ->page($page, $pagesize)
                ->select();
        }
        //这里一定要返回有list这个字段,total是可选的,如果total<=list的数量,则会隐藏分页按钮
        return json(['list' => $datalist, 'total' => $total]);
    }
    
    protected function selecttype()
    {
        //设置过滤方法
        $this->request->filter(['trim', 'strip_tags', 'htmlspecialchars']);

        //搜索关键词,客户端输入以空格分开,这里接收为数组
        $word = (array)$this->request->request("q_word/a");
        //当前页
        $page = $this->request->request("pageNumber");
        //分页大小
        $pagesize = $this->request->request("pageSize");
        //搜索条件
        $andor = $this->request->request("andOr", "and", "strtoupper");
        //排序方式
        $orderby = (array)$this->request->request("orderBy/a");
        //显示的字段
        $field = $this->request->request("showField");
        //主键
        $primarykey = $this->request->request("keyField");
        //主键值
        $primaryvalue = $this->request->request("keyValue");
        //搜索字段
        $searchfield = (array)$this->request->request("searchField/a");
        //自定义搜索条件
        $custom = (array)$this->request->request("custom/a");
        //是否返回树形结构
        $istree = $this->request->request("isTree", 0);
        $ishtml = $this->request->request("isHtml", 0);
        if ($istree) {
            $word = [];
            $pagesize = 999999;
        }
        $order = [];
        foreach ($orderby as $k => $v) {
            $order[$v[0]] = $v[1];
        }
        $field = $field ? $field : 'name';
        
        //如果有primaryvalue,说明当前是初始化传值
        if ($primaryvalue !== null) {
            $where = [$primarykey => ['in', $primaryvalue]];
            $pagesize = 999999;
        } else {
            $where = function ($query) use ($word, $andor, $field, $searchfield, $custom) {
                $logic = $andor == 'AND' ? '&' : '|';
                $searchfield = is_array($searchfield) ? implode($logic, $searchfield) : $searchfield;
                $searchfield = str_replace(',', $logic, $searchfield);
                $word = array_filter(array_unique($word));
                if (count($word) == 1) {
                    $query->where($searchfield, "like", "%" . reset($word) . "%");
                } else {
                    $query->where(function ($query) use ($word, $searchfield) {
                        foreach ($word as $index => $item) {
                            $query->whereOr(function ($query) use ($item, $searchfield) {
                                $query->where($searchfield, "like", "%{$item}%");
                            });
                        }
                    });
                }
                if ($custom && is_array($custom)) {
                    foreach ($custom as $k => $v) {
                        if (is_array($v) && 2 == count($v)) {
                            $query->where($k, trim($v[0]), $v[1]);
                        } else {
                            $query->where($k, '=', $v);
                        }
                    }
                }
            };
        }
        $model = new TypeModel;
        $total = $model->where($where)->count();
        if ($total > 0) {
           
            //如果有primaryvalue,说明当前是初始化传值,按照选择顺序排序
            if ($primaryvalue !== null && preg_match("/^[a-z0-9_\-]+$/i", $primarykey)) {
                $primaryvalue = array_unique(is_array($primaryvalue) ? $primaryvalue : explode(',', $primaryvalue));
                //修复自定义data-primary-key为字符串内容时，给排序字段添加上引号
                $primaryvalue = array_map(function ($value) {
                    return '\'' . $value . '\'';
                }, $primaryvalue);

                $primaryvalue = implode(',', $primaryvalue);

                $model->orderRaw("FIELD(`{$primarykey}`, {$primaryvalue})");
            } else {
                $model->order($order);
            }
            
            $datalist = $model->where($where)
            	 ->where(['company_id'=>$this->auth->company_id])
                ->page($page, $pagesize)
                ->select();
        }
        //这里一定要返回有list这个字段,total是可选的,如果total<=list的数量,则会隐藏分页按钮
        return json(['list' => $datalist, 'total' => $total]);
    }
    
    /**
     * 下载保存微信图片素材
     * @param  [string] $serverid 微信服务器上的素材ID
     * @return [string] 返回保存本地之后的图片路径
     */
    function getimg(){
        $serverid=$this->request->param('serverid');
        if(empty($serverid)) return false;
        $access_token=$this->getimg_token();
        $content = $this->https_request('https://api.weixin.qq.com/cgi-bin/media/get?access_token='.$access_token.'&media_id='.$serverid);
        if(json_decode($content,true)['errcode']){
            return json_decode($content,true)['errmsg'];
        }
        $filename = time().rand(001,999).'.jpg';			//保存的文件名
        $dateStr = date('Ymd');
        $file_dir =  './uploads/'.date("Ymd").'/'; //保存文件的目录
        if (!is_dir($file_dir)){       			//创建保存文件的目录
            mkdir(iconv("GBK","UTF-8", $file_dir),0777,true);
        }

        $path = $file_dir.$filename;			//文件路径
        if(file_exists($path)){
            unlink($path); 						//如果文件已经存在则删除已有的
        }

        $fp = fopen($path, 'w');
        $state = fwrite($fp,$content);  		//写入数据
        fclose($fp);

        if(!$state) return false;
        $path='/uploads/'.date("Ymd").'/'.$filename;
        return $path;
    }


    public function getimg_token(){
        //获取access_token，并缓存
        $file = RUNTIME_PATH.'/access_token1';//缓存文件名access_token1
        $appid='wx4f79233878b9f770'; // 填写自己的appid
        $secret='10eb3f75adafacbaa3c584908395c982'; // 填写自己的appsecret
        $expires = 3600;//缓存时间1个小时
        if(file_exists($file)) {
            $time = filemtime($file);
            if(time() - $time > $expires) {
                $token = null;
            }else {
                $token = file_get_contents($file);
            }
        }else{
            fopen("$file", "w+");
            $token = null;
        }
        if (!$token || strlen($token) < 6) {
            $res = file_get_contents("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$secret."");
            $res = json_decode($res, true);
            $token = $res['access_token'];
            @file_put_contents($file, $token);
        }
        return $token;
    }


    function https_request($url, $data = null,$time_out=60,$out_level="s",$headers=array())
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_NOSIGNAL, 1);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        if($out_level=="s")
        {
            //超时以秒设置
            curl_setopt($curl, CURLOPT_TIMEOUT,$time_out);//设置超时时间
        }elseif ($out_level=="ms")
        {
            curl_setopt($curl, CURLOPT_TIMEOUT_MS,$time_out);  //超时毫秒，curl 7.16.2中被加入。从PHP 5.2.3起可使用
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        if($headers)
        {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);//如果有header头 就发送header头信息
        }
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }
    
    
}
