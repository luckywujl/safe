<?php

namespace app\admin\controller\user;

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
class User extends Backend
{

    protected $relationSearch = true;
    protected $dataLimit = 'personal';
	 protected $dataLimitField = 'company_id';
    protected $searchFields = 'id,username,nickname';
    protected $noNeedRight = ['getuser','jstree','updateage'];

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
     * 查看
     */
    public function getuser()
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
                ->field('user.id as id,jobnumber as user_jobnumber,department_id as user_department_id,department.name,nickname as user_nickname')
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
                $params['password'] = \app\common\library\Auth::instance()->getEncryptPassword($params['password'], $salt);
                $params['salt'] = $salt;
                $params['jointime'] = time();
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
        $this->view->assign('groupList', build_select('row[group_id]', \app\admin\model\UserGroup::column('id,name'),'', ['class' => 'form-control selectpicker']));
        
        return $this->view->fetch();
    }

    /**
     * 编辑
     */
    public function edit($ids = null)
    {
        if ($this->request->isPost()) {
            $this->token();
        }
        $row = $this->model->get($ids);
        $this->modelValidate = true;
        if (!$row) {
            $this->error(__('No Results were found'));
        }
        $this->view->assign('groupList', build_select('row[group_id]', \app\admin\model\UserGroup::column('id,name'), $row['group_id'], ['class' => 'form-control selectpicker']));
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
        $row = $this->model->get($ids);
        $this->modelValidate = true;
        if (!$row) {
            $this->error(__('No Results were found'));
        }
        Auth::instance()->delete($row['id']);
        $this->success();
    }
    /**
     * 更新年龄
     */
    public function updateage()
    {
        if (!$this->request->isPost()) {
            $this->error(__("Invalid parameters"));
        }
        $user = $this->model->where('company_id',$this->auth->company_id)->select();
        $result = 0;
        $data = [];
        foreach($user as $k=>$v){
        		$item = [];
        		//根据身份证号计算生日和年龄
        		if(strlen($v['idcard'])==18) {
            		//$v['birthday'] = substr($v['idcard'], 6, 4).'-'.substr($v['idcard'], 10, 2).'-'.substr($v['idcard'], 12, 2);
            		$item['age'] = date("Y")-substr($v['idcard'], 6, 4);
            		$item['id'] = $v['id'];
        		}
        		$data[] = $item;
        }
        $result = $this->model->saveall($data);
        if($result) {
        
        $this->success('完成年龄更新！');
     } 
     $this->error('更新失败！');
    }
    /**
     * 导入
     */
    public function import()
    {
    	
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
        $department = new \app\admin\model\user\Department;
        $department_info = $department->field('id,name')->where(['company_id'=>$this->auth->company_id])->order('id', 'asc')->select(); 
        $department_name = array_column($department_info , 'name');//将部门名称装入一维数组，免得重复读表
        $department_id = array_column($department_info , 'id');//获取部门ID，主键
        $department_info = array_combine($department_id,$department_name);//将两个一维数组组装成键名=》键值形式
        
        $group = new \app\admin\model\UserGroup;
        $group_info = $group->field('id,name')->where(['company_id'=>$this->auth->company_id])->select();
        $group_name = array_column($group_info , 'name');//将组别名称装入一维数组，免得重复读表
        $group_id = array_column($group_info , 'id');//获取组别ID，主键
        $group_info = array_combine($group_id,$group_name);//将两个一维数组组装成键名=》键值形式
        
        $user_info = $this->model->field('id,username,mobile,email')->order('id','asc')->select();
        $user_username = array_column($user_info,'username');//将用户名抽出
        $user_mobile = array_column($user_info,'mobile');//将电话号码抽出
        $user_email = array_column($user_info,'email');//将邮箱抽出
        //以上将三列抽出，以便逐过校验

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
            	 $repeat_B = '';
            	 $repeat_C = ''; 
                $auth = Auth::instance();
                foreach ($insert as &$val) {
                    if (!isset($val['company_id']) || empty($val['company_id'])) {
                        $val['company_id'] = $this->auth->company_id;
                    }
                    $val['jointime'] = time();//导入时间
                    $val['status'] = 'normal';//状态
                    $salt = \fast\Random::alnum();//随机生成密码盐
                	  $val['password'] = \app\common\library\Auth::instance()->getEncryptPassword($val['password'], $salt);//结合明码和盐生成密码
                	  $val['salt'] = $salt;//密码盐
                	  $val['gender'] = $val['gender']=='女' ? 0:1;//完成性别转换
                	  $val['department_id'] = array_search($val['department_id'],$department_info);
                	  $val['group_id'] = array_search($val['group_id'],$group_info);
                	  //根据身份证号计算生日和年龄
                	  if(strlen($val['idcard'])==18) {
                	  		$val['birthday'] = substr($val['idcard'], 6, 4).'-'.substr($val['idcard'], 10, 2).'-'.substr($val['idcard'], 12, 2);
                	  		$val['age'] = date("Y")-substr($val['idcard'], 6, 4);
                	  }
                	  if(in_array($val['username'], $user_username)) {
                	  		$repeat_A = $repeat_A.$val['username'];
                	  }
                	  if(in_array($val['mobile'], $user_mobile)) {
                	  		$repeat_B = $repeat_B.$val['mobile'];
                	  }
                	  if(in_array($val['email'], $user_email)) {
                	  		$repeat_C = $repeat_C.$val['email'];
                	  } 
                }
            }
            if($repeat_A<>'') {
            	$this->error('以下用户名有重复:'.$repeat_A);
            }
            if($repeat_B<>'') {
            	$this->error('以下电话号码有重复:'.$repeat_B);
            }
            if($repeat_C<>'') {
            	$this->error('以下邮箱有重复:'.$repeat_C);
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
            	$this->error('以下用户名有重复:'.$repeat_A);
            }
            if($repeat_B<>'') {
            	$this->error('以下电话号码有重复:'.$repeat_B);
            }
            if($repeat_C<>'') {
            	$this->error('以下邮箱有重复:'.$repeat_C);
            }
            $this->error($e->getMessage());
        }

        $this->success();
    }
    
   
    /**
     * 批量设置
     */
    public function batch($ids = null)
    {
        //$row = $this->model->get($ids);
      
        if ($this->request->isPost()) {
          	$params = $this->request->param();//接收过滤条件
          	if(isset ($params['content'])&isset ($params['content'])) {
          	$result = '';
          	Db::startTrans();
                try {
                    	if($params['type']==1) {
          					$result = $this->model->where('id','in',$params['ids'])->update(['group_id'=>$params['content']]);
          				}
          				if($params['type']==2) {
          					$result = $this->model->where('id','in',$params['ids'])->update(['department_id'=>$params['content']]);
          				}
          				if($params['type']==3) {
          					$result = $this->model->where('id','in',$params['ids'])->update(['status'=>$params['content']]);
          				}
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
          	
          	if(!$result) {
          	$this->success('完成');
          } else {
          	$this->error('更新失败，请重试！');
          }
       } else {
       	$this->error($params['type'].'-'.$params['ids']);
       }
            //$this->error($params['type'].'-'.$params['ids']);
            
        }
        
        $this->view->assign('groupList', build_select('row[group_id]', \app\admin\model\UserGroup::column('id,name'), '', ['id'=>'c-group_id','class' => 'form-control selectpicker']));
        $this->assignconfig("ids", $ids);
        return $this->view->fetch();
    }
    

}
