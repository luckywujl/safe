<?php

namespace addons\training\controller;

use addons\training\model\Main as MainModel;
use addons\training\model\Course as CourseModel;
use addons\training\model\Record as RecordModel;
use addons\training\model\Result as ResultModel;

class Course extends Base
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

    public function index()
    {
        $config = get_addon_config('training');

        $model = new CourseModel;
        $main_id = $this->request->param('main_id');
        $course_id = $this->request->param('course_id');
        if (!$main_id) {
            $this->redirect(addon_url("/training/Index/alert",['msg'=>'未获取到课程']));
        }
        $main = MainModel::get($main_id);
        if(!$main){
            $this->redirect(addon_url("/training/Index/alert",['msg'=>'培训尚未安排课程，请等待管理员设置']));
        }
        $time = strtotime(date("Y-m-d H:i:s"));
        if($main['starttime'] > $time && $main['endtime'] > $time){
            $this->redirect(addon_url("/training/Index/alert",['msg'=>'培训尚未开始']));
        }else if($main['endtime'] < $time && $main['starttime'] < $time && $config['pastview'] !== '1'){
            $this->redirect(addon_url("/training/Index/alert",['msg'=>'该培训已经结束了']));
        }else{
            $list = $model->with(['record'=>function ($query) use($main_id){
                $query->where('user_id', $this->auth->id)->where('training_main_id',$main_id);
            }])->where('status', 'normal')->where('id', 'in', $main['training_course_ids'])->where('deletetime', 'null')->order('weigh DES')->select();
            $this->view->assign('main', $main);
            $this->view->assign('main_id', $main['id']);
            $this->view->assign('list', $list);
            $this->view->assign('course_id', $course_id);
        }
        return $this->view->fetch('/course');
    }

    public function getCourse(){

    }

    public function playtimes(){
        $training_course_id = $this->request->param('training_course_id');
        $course = CourseModel::get($training_course_id);
        $course->playtimes += 1;
        $course->save();
    }

    public function play()
    {
        $studytime = $this->request->param('studytime', 0);
        $training_course_id = $this->request->param('training_course_id');
        $training_main_id = $this->request->param('training_main_id');
        $complete = $this->request->param('complete', 0);
        $main = MainModel::get($training_main_id);
        if(!$main){
            $this->error('培训尚未安排课程，请等待管理员设置');
        }
        $time = strtotime(date("Y-m-d H:i:s"));
        if($main['starttime'] <= $time && $main['endtime'] >= $time){
            $model = new RecordModel;
            $progress= 0;
            $record = $model->where('training_main_id', $training_main_id)->where('training_course_id', $training_course_id)->where('user_id', $this->auth->id)->find();
            if ($record) {
                if ($record['complete'] !== 1 && $record['studytime'] < $studytime + 1) {
                    $duration = CourseModel::where('id', $training_course_id)->value('duration');
                    $progress = round($studytime * 100 / $duration, 0)>100 ? 100 : round($studytime * 100 / $duration, 0);
                    $model->where('id', $record['id'])->update([
                        'training_course_id'  =>  $training_course_id,
                        'studytime' =>  $studytime,
                        'progress'=> $progress,
                        'complete' => $complete,
                        'updatetime'=>strtotime(date("Y-m-d H:i:s"))
                    ]);
                    \think\Hook::listen("course_completed", $training_course_id);
                    //检测是否所有课程全部完成
                    $count = count(explode(',',$main['training_course_ids']));
                    $complete = RecordModel::where('training_main_id',$training_main_id)
                    ->where('training_course_id','in',$main['training_course_ids'])
                    ->where('user_id',$this->auth->id)
                    ->where('complete','1')
                    ->count();
                    if($count == $complete){
                        //培训全部完成
                        \think\Hook::listen("training_completed", $training_main_id);
                        $isin = ResultModel::where(['user_id'=>$this->auth->id,'training_main_id'=>$training_main_id])->find();
                        if(!$isin){
                            ResultModel::create(['user_id'=>$this->auth->id,'training_main_id'=>$training_main_id]);
                        }
                    }
                }
                return json($progress);
            } else {
                $model->create([
                    'training_main_id' => $training_main_id,
                    'training_course_id'  =>  $training_course_id,
                    'user_id' =>  $this->auth->id,
                    'company_id'=> $this->auth->company_id  //加入数据归属
                ]);
                return json(0);
            }
        }
    }
}
