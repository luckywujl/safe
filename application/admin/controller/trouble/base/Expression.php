<?php

namespace app\admin\controller\trouble\base;

use app\common\controller\Backend;
use think\Db;
use fast\Tree;
/**
 * 隐患现象
 *
 * @icon fa fa-circle-o
 */
class Expression extends Backend
{
    
    /**
     * Expression模型对象
     * @var \app\admin\model\trouble\base\Expression
     */
    protected $model = null;
    protected $dataLimit = 'personal';
	 protected $dataLimitField = 'company_id';
	 protected $noNeedRight = ['index','getexpression','jstree','expression','area'];

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\trouble\base\Expression; 

        $info = $this->model->where('company_id',$this->auth->company_id)->select();
        $name = array_column($info,'name');
        $id = array_column($info,'id');
        $this->idtoname = array_combine($id,$name);

        $tree = Tree::instance();
        $tree->init(collection($this->model->where(['company_id'=>$this->auth->company_id,'level'=>['in',[0,1]]])->order('id asc')->select())->toArray(), 'pid');
        $this->typelist = $tree->getTreeList($tree->getTreeArray(0), 'name');
        $typedata = [];
        foreach ($this->typelist as $k => $v) {
            $typedata[$v['id']] = $v;
        }
        $this->view->assign("typeList", $typedata);
        $this->assignconfig("typeList", $typedata);

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
        //设置过滤方法
        $this->request->filter(['strip_tags', 'trim']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            
            $idtoname = $this->idtoname;


            $list = $this->model
                ->where($where)
                ->where(['level'=>2])
                ->order($sort, $order)
                ->paginate($limit);
            foreach($list as $k=>$v){
                $v['pname'] = $idtoname[$v['pid']];

            }

            $result = array("total" => $list->total(), "rows" => $list->items());

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
            $params = $this->request->post("row/a");
            if ($params) {
                $params = $this->preExcludeFields($params);

                if ($this->dataLimit && $this->dataLimitFieldAutoFill) {
                    $params[$this->dataLimitField] = $this->auth->company_id;
                }
                $params['level'] =2;
                // $params['pid'] =0;
                $result = false;
                Db::startTrans();
                try {
                    //是否采用模型验证
                    if ($this->modelValidate) {
                        $name = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.add' : $name) : $this->modelValidate;
                        $this->model->validateFailException(true)->validate($validate);
                    }
                    $result = $this->model->allowField(true)->save($params);
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
                if ($result !== false) {
                    $this->success();
                } else {
                    $this->error(__('No rows were inserted'));
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        $pid = $this->request->request("pid",'');
        $this->view->assign("pid", $pid);
        return $this->view->fetch();
    }
    /**
     * 查看
     */
    public function getexpression()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags', 'trim']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $info = $this->model->where('company_id',$this->auth->company_id)->select();
            $name = array_column($info,'name');
            $id = array_column($info,'id');
            $idtoname = array_combine($id,$name);


            $list = $this->model
                ->where($where)
                ->where(['level'=>2])
                ->order($sort, $order)
                ->paginate($limit);
            foreach($list as $k=>$v){
                $v['pname'] = $idtoname[$v['pid']];
                
            }

            $result = array("total" => $list->total(), "rows" => $list->items());

            return json($result);
        }
        return $this->view->fetch();
    }
    /**
     * 读取省市区数据,联动列表
     */
    public function chinaarea()
    {
        $params = $this->request->get("row/a");
        if (!empty($params)) {
            $province = isset($params['province']) ? $params['province'] : '';
            $city = isset($params['city']) ? $params['city'] : '';
        } else {
            $province = $this->request->get('province', '');
            $city = $this->request->get('city', '');
        }
        $where = ['pid' => 0, 'level' => 1];
        $provincelist = null;
        if ($province !== '') {
            $where['pid'] = $province;
            $where['level'] = 2;
            if ($city !== '') {
                $where['pid'] = $city;
                $where['level'] = 3;
            }
        }
        $provincelist = Db::name('area')->where($where)->field('name as value,name')->select();
        $this->success('', '', $provincelist);
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
        $list = [];
        $list[]=[
            'id'=>'0', 
            'pId'=>'#',
            'name'=>'全部',
            'open'=>true
        ];
        
        foreach ($this->typelist as $k => $v) {
                $v['pId']=$v['pid'];
                $v['name'] = trim(str_replace($v['spacer'],'',$v['name']));         
                $list[] = $v;    
        }
        if ($this->request->isAjax()) {
            return json($list);
        }else{
            return $list;
        }
    }

    /**
     * 读取省市区数据,联动列表
     */
    public function expression()
    {
        $params = $this->request->get("row/a");

        if (!empty($params)) {
            $kind = isset($params['kind']) ? $params['kind'] : '';       
            $type = isset($params['type']) ? $params['type'] : '';
        } else {
            $kind = $this->request->get('kind', '');
            $type = $this->request->get('type', '');
        }
        $where = ['pid' => 0, 'level' => 0];
        $provincelist = null;
        if ($kind !== '') {
            $where['pid'] = array_search($kind,$this->idtoname);
            $where['level'] = 1;
            if ($type !== '') {
                $where['pid'] = array_search($type,$this->idtoname);
                $where['level'] = 2;
            }
        }
        $provincelist = Db::name('trouble_expression')->field('name as value,name')->where($where)->select();
        $this->success('', '', $provincelist);
    }
    


}
