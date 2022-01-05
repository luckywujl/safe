<?php

namespace app\admin\controller\training;

use app\common\controller\Backend;
use think\Db;
use fast\Tree;
/**
 * 分类管理
 *
 * @icon fa fa-circle-o
 */
class Category extends Backend
{
    
    /**
     * Category模型对象
     * @var \app\admin\model\training\Category
     */
    protected $model = null;
    protected $dataLimit = 'personal';
	 protected $dataLimitField = 'company_id';
    protected $categorylist = [];
    protected $noNeedRight = ['selectpage','jstree'];
    protected $typelist = [[
        'key'=>'main',
        'name'=>'培训分类'
    ],[
        'key'=>'course',
        'name'=>'课程分类'
    ]];

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\training\Category;

        $tree = Tree::instance();
        $tree->init(collection($this->model->where(['company_id'=>$this->auth->company_id])->order('weigh desc,id desc')->select())->toArray(), 'pid');
        $this->categorylist = $tree->getTreeList($tree->getTreeArray(0), 'name');
        $categorydata = [0 => ['type' => 'all', 'name' => __('None')]];
        foreach ($this->categorylist as $k => $v) {
            $categorydata[$v['id']] = $v;
        }
        $this->view->assign("flagList", $this->model->getFlagList());
        $this->view->assign("typeList", $this->typelist);
        $this->view->assign("parentList", $categorydata);
        $this->assignconfig('typeList', $this->typelist);
    }

    public function import()
    {
        parent::import();
    }

     /**
     * 查看
     */
    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax()) {
            $search = $this->request->request("search");
            $type = $this->request->request("type");

            //构造父类select列表选项数据
            $list = [];

            foreach ($this->categorylist as $k => $v) {
                if ($search) {
                    if ($v['type'] == $type && stripos($v['name'], $search) !== false || stripos($v['nickname'], $search) !== false) {
                        if ($type == "all" || $type == null) {
                            $list = $this->categorylist;
                        } else {
                            $list[] = $v;
                        }
                    }
                } else {
                    if ($type == "all" || $type == null) {
                        $list = $this->categorylist;
                    } elseif ($v['type'] == $type) {
                        $list[] = $v;
                    }
                }
            }

            $total = count($list);
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }

    /**
     * 添加
     */
    public function add()
    {
        if ($this->request->isPost()) {
            $this->token();
        }else{
            $type = $this->request->param('type');
            $this->view->assign("typeSelected",$type);
        }
        return parent::add();
    }

    /**
     * 编辑
     */
    public function edit($ids = null)
    {
        $row = $this->model->get($ids);
        if (!$row) {
            $this->error(__('No Results were found'));
        }
        $adminIds = $this->getDataLimitAdminIds();
        if (is_array($adminIds)) {
            if (!in_array($row[$this->dataLimitField], $adminIds)) {
                $this->error(__('You have no permission'));
            }
        }
        if ($this->request->isPost()) {
            $this->token();
            $params = $this->request->post("row/a");
            if ($params) {
                $params = $this->preExcludeFields($params);

                if ($params['pid'] != $row['pid']) {
                    $childrenIds = Tree::instance()->init(collection(\app\admin\model\training\Category::select())->toArray())->getChildrenIds($row['id'], true);
                    if (in_array($params['pid'], $childrenIds)) {
                        $this->error(__('Can not change the parent to child or itself'));
                    }
                }

                try {
                    //是否采用模型验证
                    if ($this->modelValidate) {
                        $name = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.edit' : $name) : $this->modelValidate;
                        $row->validate($validate);
                    }
                    $result = $row->allowField(true)->save($params);
                    if ($result !== false) {
                        $this->success();
                    } else {
                        $this->error($row->getError());
                    }
                } catch (\think\exception\PDOException $e) {
                    $this->error($e->getMessage());
                } catch (\think\Exception $e) {
                    $this->error($e->getMessage());
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        $this->view->assign("row", $row);
        return $this->view->fetch();
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
        if ($ids) {
            $result = 0 ;
            //在删除前先验证是否有子分类
            
            $result = $this->model->where(['pid'=>['in',$ids]])->select();
            if($result){
                $this->error(__('删除失败，原因是要删除的分类有子分类，请先删除它们！'));
            }
            //再验证是否有分类课程未删除
            $coursemodel = new \app\admin\model\training\Course;
            $result = $coursemodel->where(['training_category_id'=>['in',$ids]])->select();
            if($result){
                $this->error(__('删除失败，原因是要删除的分类下有课程，请先删除他们'));
            }
            //再验证是否有分类培训未删除
            $mainmodel = new \app\admin\model\training\Main;
            $result = $mainmodel->where(['training_category_id'=>['in',$ids]])->select();
            if($result){
                $this->error(__('删除失败，原因是要删除的分类下有培训，请先删除他们'));
            }
            $pk = $this->model->getPk();
            $adminIds = $this->getDataLimitAdminIds();
            if (is_array($adminIds)) {
                $this->model->where($this->dataLimitField, 'in', $adminIds);
            }
            $list = $this->model->where($pk, 'in', $ids)->select();

            $count = 0;
            Db::startTrans();
            try {
                foreach ($list as $k => $v) {
                    $count += $v->delete();
                }
                Db::commit();
            } catch (PDOException $e) {
                Db::rollback();
                $this->error($e->getMessage());
            } catch (Exception $e) {
                Db::rollback();
                $this->error($e->getMessage());
            }
            if ($count) {
                $this->success();
            } else {
                $this->error(__('No rows were deleted'));
            }
        }
        $this->error(__('Parameter %s can not be empty', 'ids'));
    }


    /**
     * Selectpage搜索
     *
     * @internal
     */
    public function selectpage()
    {
        return parent::selectpage();
    }
    /**
     * JSTree交互式树
     *
     * @internal
     */
    public function jstree($type = '')
    {
        //设置过滤方法
        $this->request->filter(['strip_tags']);

        $type = $this->request->param("type",$type);
        $list = [];
        $list[]=[
            'id'=>'0', 
            'pId'=>'#',
            'name'=>'全部',
            'open'=>true
        ];
        foreach ($this->categorylist as $k => $v) {
                $v['pId']=$v['pid'];
                $v['name'] = trim(str_replace($v['spacer'],'',$v['name']));
                if ($type) {
                if ($type == "all" || $type == null) {
                    $list = $this->categorylist;
                } elseif ($v['type'] == $type) {
                    $list[] = $v;
                }
                }
        }
        if ($this->request->isAjax()) {
            return json($list);
        }else{
            return $list;
        }
    }
}