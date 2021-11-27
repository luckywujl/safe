<?php

namespace addons\training\controller;

use addons\training\model\Main as MainModel;
use addons\training\model\Course as CourseModel;
use addons\training\model\Record as RecordModel;
use addons\training\model\Result as ResultModel;
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
}
