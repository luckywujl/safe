<?php

namespace app\admin\controller\trouble\report;

use app\common\controller\Backend;

/**
 * 隐患告警信息
 *
 * @icon fa fa-circle-o
 */
class Duration extends Backend
{
    
    /**
     * Duration模型对象
     * @var \app\admin\model\trouble\report\Duration
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\trouble\report\Duration;
        $this->view->assign("sourceTypeList", $this->model->getSourceTypeList());
        $this->view->assign("mainStatusList", $this->model->getMainStatusList());
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
            $department = new  \app\admin\model\user\Department;
            $area = new  \app\admin\model\trouble\base\Area;
            
            $department_info = $department->where('company_id',$this->auth->company_id)->select();
            $depart_id = array_column($department_info,'id');//部门ID
            $depart_name = array_column($department_info,'name');//部门名称
            $depart_pname = array_column($department_info,'pname');//上级部门
            $name = array_combine($depart_name,$depart_id);
            $pname = array_combine($depart_pname,$depart_id);

            $list = $this->model
                    ->with(['troublepoint'])
                    ->field('troublepoint.point_department_id,count(point_id) as number,avg(firstduration) as firstduration, avg(finishduration) as finishduration')     
                    ->where($where)
                    ->group('troublepoint.point_department_id')
                    ->order($sort, $order)
                    ->paginate($limit);

            foreach ($list as $row) {
                $row['department_name'] = array_search($row['troublepoint']['point_department_id'],$name); 
            }
            $result = array("total" => $list->total(), "rows" => $list->items());
            return json($result);
        }
        return $this->view->fetch();
    }

}
