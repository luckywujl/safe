<?php

namespace app\admin\controller\training;

use app\common\controller\Backend;
use app\common\library\Auth;
use Think\Db;
use fast\Tree;
use Exception;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use think\exception\PDOException;
use think\exception\ValidateException;

/**
 * 会员管理
 *
 * @icon fa fa-user
 */
class Plan extends Backend
{

    protected $relationSearch = true;
    protected $dataLimit = 'personal';
	 protected $dataLimitField = 'company_id';
    protected $searchFields = 'id,username,nickname';
    protected $noNeedRight = ['jstree','selectmain'];

    /**
     * @var \app\admin\model\User
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('User');
        $department = new \app\admin\model\user\Department;
        $tree = Tree::instance();
        $tree->init(collection($department->where(['company_id'=>$this->auth->company_id])->order('weigh desc,id desc')->select())->toArray(), 'pid');
        $this->departmentlist = $tree->getTreeList($tree->getTreeArray(0), 'name');
        $departmentdata = [0 => ['type' => 'all', 'name' => __('None')]];
        foreach ($this->departmentlist as $k => $v) {
            $departmentdata[$v['id']] = $v;
        }
        //$this->view->assign("flagList", $this->model->getFlagList());
        //$this->view->assign("typeList", $this->typelist);
        $this->view->assign("departmentList", $departmentdata);
        $this->assignconfig("departmentList", $departmentdata);
        //$this->assignconfig("typeList", $this->typelist);
        //$this->assignconfig('typeList', $this->typelist);
    }

    /**
     * 查看
     */
    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags', 'trim']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $list = $this->model
                ->with('group,department')
                ->where($where)
                ->where(['user.company_id'=>$this->auth->company_id])
                ->order($sort, $order)
                ->paginate($limit);
            foreach ($list as $k => $v) {
                $v->avatar = $v->avatar ? cdnurl($v->avatar, true) : letter_avatar($v->nickname);
                $v->hidden(['password', 'salt']);
            }
            $result = array("total" => $list->total(), "rows" => $list->items());

            return json($result);
        }
        return $this->view->fetch();
    }
    
    
    
    
   
    /**
     * 设置课程
     */
    public function selectmain($ids = null)
    {
        //$row = $this->model->get($ids);
      
        if ($this->request->isPost()) {
          	$params = $this->request->param();//接收过滤条件
          	$result = '';
          	if($params['type']==1) {
          		$result = $this->model->where('id','in',$ids)->update(['group_id'=>$params['content']]);
          	}
          	if($params['type']==2) {
          		$result = $this->model->where('id','in',$ids)->update(['department_id'=>$params['content']]);
          	}
          	if($params['type']==3) {
          		$result = $this->model->where('id','in',$ids)->update(['status'=>$params['content']]);
          	}
          	if($result) {
          	$this->success('完成');
          }
            //$this->error($params['type'].'-'.$params['ids']);
            
        }
        
        $this->view->assign('groupList', build_select('row[group_id]', \app\admin\model\UserGroup::column('id,name'), '', ['id'=>'c-group_id','class' => 'form-control selectpicker']));
        $this->assignconfig("ids", $ids);
        return $this->view->fetch();
    }

}
