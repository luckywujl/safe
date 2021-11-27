<?php
namespace app\admin\controller\training;

use addons\training\model\Record;
use app\common\controller\Backend;
use app\admin\model\training\Main as MainModel;
use app\admin\model\training\Course as CourseModel;
use app\admin\model\training\Category as CategoryModel;
use app\common\model\User;

/**
 * 培训统计
 *
 * @icon fa fa-circle-o
 */
class Index extends Backend
{

    protected $relationSearch = true;
    protected $dataLimit = 'personal';
	 protected $dataLimitField = 'company_id';
    protected $noNeedRight = ['record','jstree'];

    public function _initialize()
    {
        parent::_initialize();
    }

    /**
     * 首页
     */
    public function index()
    {
        $now = strtotime(date("Y-m-d H:i:s"));
        $count=[];
        $count['ongoing'] = MainModel::where('starttime','<=',$now)->where('endtime','>=',$now)->where('deletetime','null')->where('status','normal')->count();
        $count['finished'] = MainModel::where('starttime','<=',$now)->where('endtime','<=',$now)->where('deletetime','null')->where('status','normal')->count();
        $count['total'] = MainModel::where('deletetime','null')->where('status','normal')->count();
        $count['notStart'] = $count['total']-$count['ongoing']-$count['finished'];
        $this->view->assign('count', $count);
        return $this->view->fetch();
    }

    public function record(){
        //设置过滤方法
        $this->request->filter(['strip_tags', 'trim']);
        if ($this->request->isAjax()) {
            $main_id = $this->request->param('main_id','');
            $course_id = $this->request->param('course_id','');
            $course=[];
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $department_model = new \app\admin\model\user\Department;
            $department_info = $department_model ->field('id,name')->where('company_id',$this->auth->company_id)->select();
            $department_id = array_column($department_info,'id');
          	$department_name = array_column($department_info,'name');
          	$department_array = array_combine($department_name,$department_id);
          	
            $main = MainModel::get($main_id);
            $users = User::field('id,group_id,department_id,username,nickname,email,mobile,avatar,level,gender,birthday,bio,score')->where(function ($query) use($main){
                $query->where('id','in',$main['user_ids'])->whereor('group_id','in',$main['user_group_ids'])->where('status','normal');
            })->where($where)->where('company_id',$this->auth->company_id)
            ->order($sort, $order)
            ->paginate($limit);
            
            foreach ($users as &$user) {
                if($course_id !== '' && $main_id !== ''){
                    $course['training_main_id']=['=', $main_id];
                    $course['training_course_id']=['in', $course_id];
                    $record = Record::where($course)->where('user_id',$user['id'])->select();
                    $studytime = 0;
                    $progress = 0;
                    $complete = 0;
                    $total = 0;
                    $count = count($record);
                    foreach ($record as $item) {
                        $studytime += $item['studytime'];
                        $progress += $item['progress'];
                        $complete += $item['complete'];
                    }
                    $list=CourseModel::where('id','in',$course_id)->where('status','normal')->where('deletetime','null')->select();
                    if(count($list) > 0 ){
                        foreach ($list as $item) {
                            $total += $item['duration'];
                        }
                    }
                    $user['department_name'] = array_search($user['department_id'],$department_array);//将lD转为名称
                    $user['record']=[
                        'total'     => $total,
                        'studytime' => $studytime,
                        'progress'  => $count == 0 ? 0 : $progress/$count,
                        'complete'  => $count == 0 ? 0 : ($complete/$count == 1) ? 1 : 0
                    ];
                    
                    
                }else{
                    $user['record']=null;
                }      
            }
            $result = array("total" => $users->total(), "rows" => $users->items());
            return json($result);
        }
        return $this->view->fetch();
    }

    /**
     * JSTree交互式树
     *
     * @internal
     */
    public function jstree()
    {
         //设置过滤方法
         $this->request->filter(['strip_tags']);
         //if ($this->request->isAjax()) {
             $type = $this->request->param('type','');
             $cagegory = collection(CategoryModel::field('id,pid,name as text,icon')->where('type','main')->order('weigh desc,id desc')->select())->toArray();
             $categorylist = $this->getTreeArray($cagegory, 'pid', 0,$type);
             return json($categorylist);
         //}
    }
    
    /**
     * 得到子级数组
     * @param int
     * @return array
     */
    protected function getChild($arr,$myid)
    {
        $newarr = [];
        foreach ($arr as $value) {
            if (!isset($value['id'])) {
                continue;
            }
            if ($value['pid'] == $myid) {
                $newarr[$value['id']] = $value;
            }
        }
        return $newarr;
    }
    /**
     *
     * 获取树状数组
     * @param string $myid       要查询的ID
     * @param string $itemprefix 前缀
     * @return array
     */
    protected function getTreeArray($arr,$pidname,$myid,$type)
    {
        $childs = $this->getChild($arr,$myid);
        $n = 0;
        $data = [];
        $time = date("Y-m-d H:i:s");
        if ($childs) {
            foreach ($childs as $id => $value) {
                if($type == 'now'){
                    $mainlist= MainModel::all(function($query) use ($value,$time){
                        $query->where('training_category_id', $value['id'])
                            ->where('status', 'normal')
                            ->where('deletetime', 'null')
                            ->whereTime('starttime','<=',$time)
                            ->whereTime('endtime','>=',$time)
                            ->field('id,name as text,status,training_course_ids');
                    }); 
                }else if($type == 'past'){
                    $mainlist= MainModel::all(function($query) use ($value,$time){
                        $query->where('training_category_id', $value['id'])
                            ->where('status', 'normal')
                            ->where('deletetime', 'null')
                            ->whereTime('endtime','<',$time)
                            ->field('id,name as text,status,training_course_ids');
                    }); 
                }else{
                    $mainlist= MainModel::all(function($query) use ($value){
                        $query->where('training_category_id', $value['id'])
                            ->where('status', 'normal')
                            ->where('deletetime', 'null')
                            ->field('id,name as text,status,training_course_ids');
                    }); 
                }
                $last = end($mainlist);
                foreach ($mainlist as $main_key => $main_val) {
                    $courselist= CourseModel::all(function($query) use ($main_val){
                        $query->where('id','in', $main_val['training_course_ids'])->where('status', 'normal')->where('deletetime', 'null')->field('id,name as text,status');
                    });
                    foreach ($courselist as $course_key => $course_val) {
                        $courselist[$course_key]=[
                            'main_id'=>$main_val['id'],
                            'course_id'=>$course_val['id'],
                            'icon'=>'fa fa-youtube-play',
                            'text'=> $course_val['text']
                        ];
                    }
                    
                    $selected = $main_val['id'] == $last['id'] ? true : false;
                    $mainlist[$main_key] = [
                        'main_id'=>$main_val['id'],
                        'course_id'  => $main_val['training_course_ids'],
                        'text'=> $main_val['text'],
                        'icon'=>'fa fa-book',
                        'checked'=> $selected,
                        'children'=>$courselist
                    ];
                }
                unset($value['flag_text']);
                unset($value['pid']);
                $data[$n] = $value;
                $data[$n]['children'] = array_merge($this->getTreeArray($arr,$pidname,$id,$type),$mainlist) ;
                $n++;
            }
        }
        return $data;
    }

}
