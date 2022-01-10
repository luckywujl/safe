<?php

namespace app\admin\controller\trouble\base;

use app\common\controller\Backend;
use app\common\library\Auth;
use Think\Db;
use fast\Tree;
use app\admin\model\setting\Company as CompanyModel;
use Exception;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use think\exception\PDOException;
use think\exception\ValidateException;
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
	 protected $searchFields =['point_name'];



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
        $this->company = $company_info;

        $this->assignconfig("company",$company_info);      
        $this->view->assign("parentList", $departmentdata);


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
            $params = $this->request->post("row/a");
            if ($params) {
                $params = $this->preExcludeFields($params);
                $result = false;
                Db::startTrans();
                try {
                    //是否采用模型验证
                    if ($this->modelValidate) {
                        $name = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.edit' : $name) : $this->modelValidate;
                        $row->validateFailException(true)->validate($validate);
                    }
                    $result = $row->allowField(true)->save($params);
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
                    $this->error(__('No rows were updated'));
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        $row['websit'] = $this->company['company_websit'];
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
            //再验证是否有分类培训未删除
            $mainmodel = new \app\admin\model\trouble\trouble\Main;
            $result = $mainmodel->where(['point_id'=>['in',$ids]])->select();
            if($result){
                $this->error(__('删除失败，原因是要删除的隐患信息点已存在报警信息，不能删除！'));
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
                $this->error(__('No rows were selected'));
            }
        }
        return $this->view->fetch();
    }
    /**
     * 导入
     */
    public function import()
    {
        $department = new \app\admin\model\user\Department;
        $department_info = $department->field('id,name')->where(['company_id'=>$this->auth->company_id])->order('id', 'asc')->select(); 
        $department_name = array_column($department_info , 'name');//将部门名称装入一维数组，免得重复读表
        $department_id = array_column($department_info , 'id');//获取部门ID，主键
        $department_info = array_combine($department_id,$department_name);//将两个一维数组组装成键名=》键值形式
        
        $area = new \app\admin\model\trouble\base\Area;
        $area_info = $area->field('id,area_name')->where(['company_id'=>$this->auth->company_id])->order('id', 'asc')->select(); 
        $area_name = array_column($area_info , 'area_name');//将区域名称装入一维数组，免得重复读表
        $area_id = array_column($area_info , 'id');//获取区载ID，主键
        $area_info = array_combine($area_id,$area_name);//将两个一维数组组装成键名=》键值形式
        
        $point_info = $this->model->field('id,point_code')->order('id','asc')->select();
       
        $point_code = array_column($point_info,'point_code');//将区域编号抽出
        //以上将编号一列抽出，以便逐过校验
        
        $file = $this->request->request('file');
        if (!$file) {
            $this->error(__('Parameter %s can not be empty', 'file'));
        }
        $filePath = ROOT_PATH . DS . 'public' . DS . $file;
        if (!is_file($filePath)) {
            $this->error(__('No results were found'));
        }
        //实例化reader
        $ext = pathinfo($filePath, PATHINFO_EXTENSION);
        if (!in_array($ext, ['csv', 'xls', 'xlsx'])) {
            $this->error(__('Unknown data format'));
        }
        if ($ext === 'csv') {
            $file = fopen($filePath, 'r');
            $filePath = tempnam(sys_get_temp_dir(), 'import_csv');
            $fp = fopen($filePath, "w");
            $n = 0;
            while ($line = fgets($file)) {
                $line = rtrim($line, "\n\r\0");
                $encoding = mb_detect_encoding($line, ['utf-8', 'gbk', 'latin1', 'big5']);
                if ($encoding != 'utf-8') {
                    $line = mb_convert_encoding($line, 'utf-8', $encoding);
                }
                if ($n == 0 || preg_match('/^".*"$/', $line)) {
                    fwrite($fp, $line . "\n");
                } else {
                    fwrite($fp, '"' . str_replace(['"', ','], ['""', '","'], $line) . "\"\n");
                }
                $n++;
            }
            fclose($file) || fclose($fp);

            $reader = new Csv();
        } elseif ($ext === 'xls') {
            $reader = new Xls();
        } else {
            $reader = new Xlsx();
        }

        //导入文件首行类型,默认是注释,如果需要使用字段名称请使用name
        $importHeadType = isset($this->importHeadType) ? $this->importHeadType : 'comment';

        $table = $this->model->getQuery()->getTable();
        $database = \think\Config::get('database.database');
        $fieldArr = [];
        $list = db()->query("SELECT COLUMN_NAME,COLUMN_COMMENT FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = ? AND TABLE_SCHEMA = ?", [$table, $database]);
        foreach ($list as $k => $v) {
            if ($importHeadType == 'comment') {
                $fieldArr[$v['COLUMN_COMMENT']] = $v['COLUMN_NAME'];
            } else {
                $fieldArr[$v['COLUMN_NAME']] = $v['COLUMN_NAME'];
            }
        }

        //加载文件
        $insert = [];
        try {
            if (!$PHPExcel = $reader->load($filePath)) {
                $this->error(__('Unknown data format'));
            }
            $currentSheet = $PHPExcel->getSheet(0);  //读取文件中的第一个工作表
            $allColumn = $currentSheet->getHighestDataColumn(); //取得最大的列号
            $allRow = $currentSheet->getHighestRow(); //取得一共有多少行
            $maxColumnNumber = Coordinate::columnIndexFromString($allColumn);
            $fields = [];
            for ($currentRow = 1; $currentRow <= 1; $currentRow++) {
                for ($currentColumn = 1; $currentColumn <= $maxColumnNumber; $currentColumn++) {
                    $val = $currentSheet->getCellByColumnAndRow($currentColumn, $currentRow)->getValue();
                    $fields[] = $val;
                }
            }

            for ($currentRow = 2; $currentRow <= $allRow; $currentRow++) {
                $values = [];
                for ($currentColumn = 1; $currentColumn <= $maxColumnNumber; $currentColumn++) {
                    $val = $currentSheet->getCellByColumnAndRow($currentColumn, $currentRow)->getValue();
                    $values[] = is_null($val) ? '' : $val;
                }
                $row = [];
                $temp = array_combine($fields, $values);
                foreach ($temp as $k => $v) {
                    if (isset($fieldArr[$k]) && $k !== '') {
                        $row[$fieldArr[$k]] = $v;
                    }
                }
                if ($row) {
                    $insert[] = $row;
                }
            }
        } catch (Exception $exception) {
            $this->error($exception->getMessage());
        }
        if (!$insert) {
            $this->error(__('No rows were updated'));
        }

        try {
            //是否包含admin_id字段
            $has_admin_id = false;
            foreach ($fieldArr as $name => $key) {
                if ($key == 'company_id') {
                    $has_admin_id = true;
                    break;
                }
            }
            
            if ($has_admin_id) {
                
            
                $repeat_A = '';
            	 
            	
                $auth = Auth::instance();
                foreach ($insert as &$val) {
                    if (!isset($val['company_id']) || empty($val['company_id'])) {
                        $val['company_id'] = $this->auth->company_id;
                    }
                    
                	  $val['point_department_id'] = array_search($val['point_department_id'],$department_info); //将表格的部门名称转换成部门ID
                	  $val['point_area_id'] = array_search($val['point_area_id'],$area_info);//将表格听区域名称转换成区域ID
                	  
                	  if(in_array($val['point_code'], $point_code)) {
                	  		$repeat_A = $repeat_A.$val['point_code'].'、';
                	  }
                	  
                }
            }
            if($repeat_A<>'') {
            	$this->error('以下隐患点编号有重复:'.$repeat_A);
            }
            
            $this->model->saveAll($insert);
        } catch (PDOException $exception) {
            $msg = $exception->getMessage();
            if (preg_match("/.+Integrity constraint violation: 1062 Duplicate entry '(.+)' for key '(.+)'/is", $msg, $matches)) {
                $msg = "导入失败，包含【{$matches[1]}】的记录已存在";
            };
            $this->error($msg);
        } catch (Exception $e) {
        	   if($repeat_A<>'') {
            	$this->error('以下隐患点编号有重复:'.$repeat_A);
            }
            $this->error($e->getMessage());
        }

        $this->success();
    }

}
