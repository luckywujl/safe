<?php

namespace app\admin\controller\trouble\base;

use app\common\controller\Backend;
use Think\Db;
use fast\Tree;
use app\admin\model\setting\Company as CompanyModel;
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
	 protected $noNeedRight = ['getpoint','jstree'];



    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\trouble\base\Point;
        $company = new \app\admin\model\setting\Company;
        
        $department = new \app\admin\model\user\Department;
        
        $tree = Tree::instance();
        $tree->init(collection($department->where(['company_id'=>$this->auth->company_id])->order('weigh desc,id desc')->select())->toArray(), 'pid');
        $this->departmentlist = $tree->getTreeList($tree->getTreeArray(0), 'name');
        $departmentdata = [0 => ['type' => 'all', 'name' => __('None')]];
        foreach ($this->departmentlist as $k => $v) {
            $departmentdata[$v['id']] = $v;
        }
        
        $company_info = $company->where('company_id',$this->auth->company_id)->find();
        $this->assignconfig("company",$company_info);      
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
                $salt = \fast\Random::alnum();
                
              
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
       
        $department = $this->request->request("department_id",'');
        $this->view->assign("department_id", $department);
        return $this->view->fetch();
    }
    
    /**
     * 查看
     */
    public function getpoint()
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
     /**
     * 下载全部二维码
     */
     public function downqrpic()
     {
        require_once "Zipfile.php";
        $dfile = tempnam('/tmp', 'tmp');//产生一个临时文件，用于缓存下载文件 
        $zip = new Zipfile();
        $filename = '隐患点报警二维码.zip'; //下载的默认文件名
        $zip->add_path(ROOT_PATH . 'public/uploads/qrcode/2/');
        $zip->output($dfile);
        // 下载文件 
        ob_clean(); 
        header('Pragma: public'); 
        header('Last-Modified:'.gmdate('D, d M Y H:i:s') . 'GMT'); 
        header('Cache-Control:no-store, no-cache, must-revalidate'); 
        header('Cache-Control:pre-check=0, post-check=0, max-age=0'); 
        header('Content-Transfer-Encoding:binary'); 
        header('Content-Encoding:none'); 
        header('Content-type:multipart/form-data'); 
        header('Content-Disposition:attachment; filename="'.$filename.'"'); 
        //设置下载的默认文件名 
        header('Content-length:'. filesize($dfile)); 
        $fp = fopen($dfile, 'r'); 
        while(connection_status() == 0 && $buf = @fread($fp, 8192)){
        	 echo $buf;
        }
        fclose($fp); 
        @unlink($dfile); 
        @flush(); 
        @ob_flush(); 
        exit();
    }
    /**
     * 下载选中二维码
     */
    public function downqrcode($ids = "")//下载选中的二维码
    {
        require_once "Zipfile.php";
        $dfile = tempnam('/tmp', 'tmp');//产生一个临时文件，用于缓存下载文件 
        $zip = new Zipfile();
        $filename = '隐患点报警二维码.zip'; //下载的默认文件名
        $ids = $ids ? $ids : $this->request->get("ids");
        if ($ids) {
            $pk = $this->model->getPk();
            $adminIds = $this->getDataLimitAdminIds();
            if (is_array($adminIds)) {
                $this->model->where($this->dataLimitField, 'in', $adminIds);
            }
            $list = $this->model->where($pk, 'in', $ids)->select();
            $count = 0;
                foreach ($list as $k => $v) {
        				$zip->add_file(file_get_contents(ROOT_PATH . 'public/uploads/qrcode/2/'.$v['point_code'].'.png'), $v['point_code']);
        				$count+=1;
                }

            if ($count) {
                $zip->output($dfile);
        			// 下载文件 
        			ob_clean(); 
        			header('Pragma: public'); 
        			header('Last-Modified:'.gmdate('D, d M Y H:i:s') . 'GMT'); 
        			header('Cache-Control:no-store, no-cache, must-revalidate'); 
        			header('Cache-Control:pre-check=0, post-check=0, max-age=0'); 
        			header('Content-Transfer-Encoding:binary'); 
        			header('Content-Encoding:none'); 
        			header('Content-type:multipart/form-data'); 
        			header('Content-Disposition:attachment; filename="'.$filename.'"'); 
        			//设置下载的默认文件名 
        			header('Content-length:'. filesize($dfile)); 
        			$fp = fopen($dfile, 'r'); 
        			while(connection_status() == 0 && $buf = @fread($fp, 8192)){
        	 			echo $buf;
        			}
        			fclose($fp); 
        			@unlink($dfile); 
        			@flush(); 
        			@ob_flush(); 
        			exit();
            } else {
                $this->error(__('No rows were deleted'));
            }
        }
        return $this->view->fetch();
    }

}
