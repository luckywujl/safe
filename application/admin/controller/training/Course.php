<?php

namespace app\admin\controller\training;

use app\common\controller\Backend;
use fast\Tree;
use app\admin\model\training\Main as MainModel;
/**
 * 课程管理
 *
 * @icon fa fa-circle-o
 */
class Course extends Backend
{
    
    /**
     * Course模型对象
     * @var \app\admin\model\training\Course
     */
    protected $model = null;
 
    protected $dataLimit = false;

    protected $noNeedRight = ['select','getCourse'];

    public function _initialize()
    {
 	parent::_initialize();
        $config = get_addon_config('training');
        if($config['datalimit'] !== "false"){
           $this->dataLimit = $config['datalimit'];
        }
        $this->model = new \app\admin\model\training\Course;
        $this->view->assign("statusList", $this->model->getStatusList());
        
        $tree = Tree::instance();
        $category_model = new \app\admin\model\training\Category;
        $tree->init(collection($category_model->order('weigh desc,id desc')->where('type','course')->select())->toArray(), 'pid');
        $this->categorylist = $tree->getTreeList($tree->getTreeArray(0), 'name');
        $categorydata = [];
        foreach ($this->categorylist as $k => $v) {
            $categorydata[$v['id']] = $v;
        }
        $this->view->assign("parentList", $categorydata);
    }

    public function import()
    {
        parent::import();
    }
    /**
     * 选择课程
     */
    public function select()
    {
        if ($this->request->isAjax()) {
            return $this->index();
        }
        return $this->view->fetch();
    }
    
    public function getCourse(){
        if ($this->request->isAjax()) {
            $ids = $this->request->request("ids", null);
            if ($ids) {
                $result = $this->model->where("id",'in',$ids)->where('deletetime', 'null')->select();
                return json($result);
            }
            return json(null);
        }
    }
    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */
    public function add()
    {
        $category = $this->request->request("category",'');
        $this->view->assign("category", $category);
        return parent::add();
    }
    
    public function edit($ids = null){
        if ($this->request->isAjax()) {
            $ids = $ids ? $ids : $this->request->post("ids");
            if ($ids) {
               $isBind =  MainModel::where('find_in_set('.$ids.',training_course_ids)')->count();
                if($isBind){
                	//将此行注释掉，允许课程在加入培训后还可以修改，但不能删除，否则在加入培训后想修改课程就太麻烦了
                    //$this->error('当前课程已在培训中选定，请先解除选定后再修改内容');
                }
            }
        }
        return parent::edit($ids);
    }

     /**
     * 删除
     */
    public function del($ids = "")
    {
        if (!$this->request->isPost()) {
            $this->error(__("Invalid parameters"));
        }
        $ids = $ids ? $ids : $this->request->post("ids");
        $isSelected = MainModel::where('find_in_set('.$ids.',training_course_ids)')->column('id');
        $toDelete = explode(',', $ids);
        $_result = [];
        foreach ($toDelete as $v){
            if(!in_array($v,$isSelected))
            {
                $_result[] = $v;
            }
        }
        if(sizeof($_result) == 0){
            $this->error("请注意：已经在培训中的课程无法直接删除，请先在培训中移除该课程！");
        }
        return parent::del($_result);
    }
}
