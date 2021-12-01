<?php

namespace addons\training\controller;

use addons\training\model\Main as MainModel;
use addons\training\model\Course as CourseModel;
use addons\training\model\Record as RecordModel;
use addons\training\model\Result as ResultModel;
use app\common\model\User;
use Think\Db;

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
    }

    public function index()
    {
        if ($this->request->isAjax()) {
            $model = new MainModel;
            $group = $this->request->param('group', 'learning');
            $year = $this->request->param('year', date('Y'));
            $category_id = $this->request->param('category_id', '#');
            $time = date("Y-m-d H:i:s");

            list($where, $sort, $order, $offset, $limit) = $this->buildparams($model);

            $list_obj = $model
            ->where(['status'=>'normal','type'=>'online'])  //20211118加入仅显示在线培训
            ->where('deletetime', 'null')
            ->where($where)
            ->where(function ($query) {
                $query->where('find_in_set('.$this->user_id.',user_ids)')->whereor('find_in_set('.$this->group_id.',user_group_ids)');
            })
            ->field("*,'{$group}' as type")
            ->order($sort, $order);

            if ($group == "future") {
                $list_obj = $list_obj->whereTime('starttime', '>', $time)->whereTime('endtime', '>', $time);
            } else if ($group == "past") {
                $list_obj = $list_obj->whereTime('starttime', '<', $time)->whereTime('endtime', '<', $time);
            } else if ($group == "completed") {
                $result = ResultModel::where('user_id', $this->user_id)->column('training_main_id');
                $list_obj = $list_obj->where('id', 'in', implode(',', $result))->whereTime('starttime', '<=', $time)->whereTime('endtime', '>=', $time);
            } else if ($group == "unlearned") {
                $result = ResultModel::where('user_id', $this->auth->id)->column('training_main_id');
                $record = RecordModel::where('user_id', $this->auth->id)->column('training_main_id');
                $list_obj = $list_obj->whereTime('starttime', '<=', $time)
                                    ->whereTime('endtime', '>=', $time)
                                    ->where('id', 'not in', implode(',', $result))
                                    ->where('id', 'not in', implode(',', $record));
            } else if ($group == "learning") {
                $result = ResultModel::where('user_id', $this->auth->id)->column('training_main_id');
                $record = RecordModel::where('user_id', $this->auth->id)->column('training_main_id');
                $list_obj = $list_obj->whereTime('starttime', '<=', $time)
                                    ->whereTime('endtime', '>=', $time)
                                    ->where('id', 'not in', implode(',', $result))
                                    ->where('id', 'in', implode(',', $record));
            }

            if ($year !== '') {
                $list_obj = $list_obj->whereTime('starttime', 'between', ["{$year}-1-1","{$year}-12-31"]);
            }
            if ($category_id !== '#') {
                $list_obj = $list_obj->where('training_category_id', $category_id);
            }
            $list = $list_obj->paginate($limit);
            $result = array("total" => $list->total(), "rows" => $list->items());
            return json($result);
        }

        $totalStudyTime = RecordModel::where('user_id', $this->auth->id)->sum('studytime');
        $totalStudyCourse = RecordModel::where('user_id', $this->auth->id)->count();
        $totalStudyMain = ResultModel::where('user_id', $this->auth->id)->count();
        $this->view->assign('totalStudyTime', $totalStudyTime?:0);
        $this->view->assign('totalStudyCourse', $totalStudyCourse?:0);
        $this->view->assign('totalStudyMain', $totalStudyMain?:0);
        return $this->view->fetch('/index');
    }
    public function getCount()
    {
            $config = get_addon_config('training');
            $model = new MainModel;
            $year = $this->request->param('year', date('Y'));
            $category_id = $this->request->param('category_id', '#');
            $time = date("Y-m-d H:i:s");
            $count=[];
            $map = [];
            $map['status']=['=','normal'];
            $map['type']=['=','online'];  //20211118加入仅显示在线培训
            if ($year !== '') {
                $map['starttime']=['between time',["{$year}-1-1","{$year}-12-31"]];
            }
            if ($category_id !== '#') {
                $map['training_category_id']=['=',$category_id];
            }

            $result = ResultModel::where('user_id', $this->user_id)->column('training_main_id');
            $record = RecordModel::where('user_id', $this->auth->id)->column('training_main_id');
            if($config['future_show'] == '1'){
                $count['future'] = $model->where(function ($query) {
                    $query->where('find_in_set('.$this->user_id.',user_ids)')->whereor('find_in_set('.$this->group_id.',user_group_ids)');
                })->where($map)
                ->where('deletetime', 'null')
                ->whereTime('starttime', '>', $time)
                ->whereTime('endtime', '>', $time)
                ->count();
            }
            $count['past'] = $model->where(function ($query) {
                $query->where('find_in_set('.$this->user_id.',user_ids)')->whereor('find_in_set('.$this->group_id.',user_group_ids)');
            })->where($map)
            ->where('deletetime', 'null')
            ->whereTime('starttime', '<', $time)
            ->whereTime('endtime', '<', $time)
            ->count();

            $count['completed'] = $model->where(function ($query) {
                $query->where('find_in_set('.$this->user_id.',user_ids)')->whereor('find_in_set('.$this->group_id.',user_group_ids)');
            })->where($map)
            ->where('deletetime', 'null')
            ->where('id', 'in', implode(',', $result))
            ->whereTime('starttime', '<=', $time)
            ->whereTime('endtime', '>=', $time)
            ->count();

            $count['unlearned'] = $model->where(function ($query) {
                $query->where('find_in_set('.$this->user_id.',user_ids)')->whereor('find_in_set('.$this->group_id.',user_group_ids)');
            })->where($map)
            ->where('deletetime', 'null')
            ->whereTime('starttime', '<=', $time)
            ->whereTime('endtime', '>=', $time)
            ->where('id', 'not in', implode(',', $result))
            ->where('id', 'not in', implode(',', $record))
            ->count();

            $count['learning'] = $model->where(function ($query) {
                $query->where('find_in_set('.$this->user_id.',user_ids)')->whereor('find_in_set('.$this->group_id.',user_group_ids)');
            })->where($map)
            ->where('deletetime', 'null')
            ->whereTime('starttime', '<=', $time)
            ->whereTime('endtime', '>=', $time)
            ->where('id', 'not in', implode(',', $result))
            ->where('id', 'in', implode(',', $record))
            ->count();
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
    //扫码签到
    public function signin()
    {
    	  if ($this->request->isPost()) { 
        	 $params = $this->request->param();//接收过滤条件	
        	 $record = new RecordModel;
        	 $main = new MainModel;
        	 $main_info =  $main->field('training_course_ids')->where('id', $params['id'])->find();//获取培训中包含的课程
        	 $course_ids = explode(',',$main_info['training_course_ids']);
        	 $data = [];
        	 foreach($course_ids as $k=>$v){
        	 		$data_r=[];
        	 		$data_r['user_id'] = $this->auth->id;
        	 		$data_r['training_main_id'] = $params['id'];
        	 		$data_r['training_course_id'] = $v;
        	 		$data_r['studytime'] = 0;
        	 		$data_r['progress'] = 0;
        	 		$data_r['complete'] = 0;
        	 		$data_r['createtime'] = time();
        	 		$data_r['updatetime'] =time();
        	 		$data_r['company_id'] = $this->auth->company_id;
        	 		$data[] = $data_r;
        	 }
        	 $result = $record->saveall($data);
        	 
        	 if($result) {
        	 
        	 $this->success('签到成功！');       
        }else {
        	$this->error('签到失败！');
        }
        }
    	  $id = $this->request->param('id');
    	  $model = new MainModel;
    	  $time = date("Y-m-d H:i:s");
    	  $main = $model
    	  		->whereTime('starttime', '<=', $time)
            ->whereTime('endtime', '>=', $time)  //需要在培训时间段内扫码签到
            ->where(['id'=>$id,'company_id'=>$this->auth->company_id,'type'=>'offline'])->find();

        if($main) {
        	$main['user_nickname'] = $this->auth->nickname;
        	$this->view->assign('signin', $main);
         return $this->view->fetch('/signin');
       }else {
       	return $this->view->fetch('/signinerror');
       }
        
    }
    //扫码签退
    public function signout()
    {
    	  if ($this->request->isPost()) { 
        	 $params = $this->request->param();//接收过滤条件	
        	 $record = new RecordModel;
        	 $log = new ResultModel;
        	 $data_r = [];
        	 $data_l = [];
        	 $data_l['user_id'] = $this->auth->id;
        	 $data_l['training_main_id'] = $params['id'];
        	 $data_l['createtime'] = time();
        	 $data_l['updatetime'] = time();
        	 $data_l['company_id'] = $this->auth->company_id;
        	 $record_in = $record->where(['training_main_id'=>$params['id'],'user_id'=>$this->auth->id,'company_id'=>$this->auth->company_id])->find();
        	 if($record_in) {
        	 	$data_r['studytime'] = round((time()-$record_in['createtime'])/1000);
        	 } else {
        	 	$this->error('您能可没有成功签到！');
        	 }
        	 $main = new MainModel;
        	 $main_info =  $main->field('training_course_ids')->where('id', $params['id'])->find();//获取培训中包含的课程
        	 
        	 $data_r['updatetime'] = time();
        	 $data_r['complete'] = 1;
        	 $data_r['progress'] = 100;
        	 Db::startTrans();
        	 try {
        	 	$result_r = $record->where(['training_main_id'=>$params['id'],'user_id'=>$this->auth->id,'company_id'=>$this->auth->company_id])->update($data_r);//更新培训记录
        	 	$result_l = $log->save($data_l);//完成培训记录
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
        	 if($result_l) {
        	 $this->success('签退成功！');       
        }else {
        	$this->error('签退失败！');
        }
        }
    	  $id = $this->request->param('id');
    	  $model = new MainModel;
    	  $time = date("Y-m-d H:i:s");
    	  $main = $model
    	  		->whereTime('starttime', '<=', $time)
            ->whereTime('endtime', '>=', $time)  //需要在培训时间段内扫码签到
            ->where(['id'=>$id,'company_id'=>$this->auth->company_id,'type'=>'offline'])->find();  
        if($main) {
        	$main['user_nickname'] = $this->auth->nickname;
        	$this->view->assign('signout', $main);
         return $this->view->fetch('/signout');
       }else {
       	return $this->view->fetch('/signouterror');
       }
        
    }
}
