<?php

namespace app\admin\controller\trouble\base;

use app\common\controller\Backend;
use Think\Db;
use fast\Tree;
/**
 * 隐患点信息
 *
 * @icon fa fa-circle-o
 */
class Point extends Backend
{
    
    /**
     * Point模型对象
     * @var \app\admin\model\trouble\base\Point
     */
    protected $model = null;
    protected $dataLimit = 'personal';
	 protected $dataLimitField = 'company_id';


    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\trouble\base\Point;
        
        $department = new \app\admin\model\user\Department;
        $tree = Tree::instance();
        $tree->init(collection($department->where(['company_id'=>$this->auth->company_id])->order('weigh desc,id desc')->select())->toArray(), 'pid');
        $this->departmentlist = $tree->getTreeList($tree->getTreeArray(0), 'name');
        $departmentdata = [0 => ['type' => 'all', 'name' => __('None')]];
        foreach ($this->departmentlist as $k => $v) {
            $departmentdata[$v['id']] = $v;
        }
       
        $this->view->assign("parentList", $departmentdata);

    }

    public function import()
    {
        parent::import();
    }

    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */
    

    /**
     * 查看
     */
    public function index()
    {
        //当前是否为关联查询
        $this->relationSearch = true;
        //设置过滤方法
        $this->request->filter(['strip_tags', 'trim']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();

            $list = $this->model
                    ->with(['troublearea','userdepartment'])
                    ->where($where)
                    ->order($sort, $order)
                    ->paginate($limit);

            foreach ($list as $row) {
                
                
            }

            $result = array("total" => $list->total(), "rows" => $list->items());

            return json($result);
        }
        return $this->view->fetch();
    }

}
