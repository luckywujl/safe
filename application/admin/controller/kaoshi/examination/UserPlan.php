<?php

namespace app\admin\controller\kaoshi\examination;

use app\common\controller\Backend;
use Think\Db;

/**
 *
 *
 * @icon fa fa-circle-o
 */
class UserPlan extends Backend
{

    /**
     * UserPlan模型对象
     * @var \app\admin\model\kaoshi\examination\KaoshiUserPlan
     */
    protected $model = null;
    protected $dataLimit = 'personal';
	 protected $dataLimitField = 'company_id';
	 
	 protected $noNeedRight = ['index','input'];

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\kaoshi\examination\KaoshiUserPlan;
        $this->view->assign("statusList", $this->model->getStatusList());
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
        //$this->dataLimit = false;
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
            $total = $this->model
                ->with(['plan', 'user','userexams'])
                ->where($where)
                ->order($sort, $order)
                ->count();

            $list = $this->model
                ->with(['plan', 'user','userexams'])
                ->where($where)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();

            foreach ($list as $row) {
                $exam_id = $row['plan']['exam_id'];
                $row['exams'] = Db::name('KaoshiExams')->where('id', $exam_id)->find();

            }
            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }
    /**
     * 查看
     */
    public function input()
    {
        //$this->dataLimit = false;
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
            $total = $this->model
                ->with(['plan', 'user','userexams'])
                ->where($where)
                ->order($sort, $order)
                ->count();

            $list = $this->model
                ->with(['plan', 'user','userexams'])
                ->where($where)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();

            foreach ($list as $row) {
                $exam_id = $row['plan']['exam_id'];
                $row['exams'] = Db::name('KaoshiExams')->where('id', $exam_id)->find();

            }
            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }

        return $this->view->fetch();
    }


}
