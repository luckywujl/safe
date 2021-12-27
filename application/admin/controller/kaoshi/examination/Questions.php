<?php

namespace app\admin\controller\kaoshi\examination;

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
 * 试题管理
 *
 * @icon fa fa-circle-o
 */
class Questions extends Backend
{

    /**
     * Questions模型对象
     * @var \app\admin\model\kaoshi\examination\KaoshiQuestions
     */
    protected $model = null;
    protected $dataLimit = 'personal';
	 protected $dataLimitField = 'company_id';


    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\kaoshi\examination\KaoshiQuestions;
        $subject = new \app\admin\model\kaoshi\KaoshiSubject;
        $tree = Tree::instance();
        $tree->init(collection($subject->where(['company_id'=>$this->auth->company_id])->order('weigh desc,id desc')->select())->toArray(), 'pid');
        $this->subjectlist = $tree->getTreeList($tree->getTreeArray(0), 'subject_name');
        $subjectdata = [0 => ['subject_name' => __('None')]];
        foreach ($this->subjectlist as $k => $v) {
            $subjectdata[$v['id']] = $v;
        }
        $this->view->assign("subjectList", $subjectdata);
        $this->assignconfig("subjectList", $subjectdata);
        $this->view->assign("typeList", $this->model->getTypeList());
        $this->view->assign("levelList", $this->model->getLevelList());
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
                ->with(['subject', 'admin'])
                ->where($where)
                ->order($sort, $order)
                ->count();

            $list = $this->model
                ->with(['subject', 'admin'])
                ->where($where)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();

            foreach ($list as $row) {


            }
            $list = collection($list)->toArray();
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
        $selectdata = [
            [
                ['key' => 'A', 'value' => ''],
                ['key' => 'B', 'value' => ''],
                ['key' => 'C', 'value' => ''],
                ['key' => 'D', 'value' => ''],
            ],
            [
                ['key' => 'A', 'value' => ''],
                ['key' => 'B', 'value' => ''],
                ['key' => 'C', 'value' => ''],
                ['key' => 'D', 'value' => ''],

            ],
            [
                ['key' => 'A', 'value' => '对'],
                ['key' => 'B', 'value' => '错'],

            ],
        ];
        $this->view->assign("selectdata", $selectdata);
        $subject_id = $this->request->request("subject_id",'');
        $this->view->assign("subject_id", $subject_id);
        if ($this->request->isPost()) {

            $params = $this->request->post("row/a");
            if ($params) {
                $params = $this->preExcludeFields($params);
                if ($this->dataLimit && $this->dataLimitFieldAutoFill) {
                    $params[$this->dataLimitField] = $this->auth->company_id;
                }
                $params['admin_id'] = $this->auth->id;
                $type = intval($params['type']) - 1;
                $params['selectdata'] = $params['selectdata' . $type];
                $selectarr = json_decode($params['selectdata'], true);
                $params['selectnumber'] = count($selectarr);
                if (!array_key_exists('answer' . $type, $params)) {
                    $this->error("请选择正确答案!");
                }
                $params['answer'] = $params['answer' . $type];


                foreach ($selectarr as $key => $value) {
                    if (empty($value['key']) && $value['key'] != '0') {
                        $this->error("请填写选项" . ($key + 1));
                    }
                    if (empty($value['value']) && $value['value'] != '0') {
                        $this->error("请填写选项" . ($key + 1) . "答案内容");
                    }
                }


                if (count(array_unique(array_map('strtolower', array_column($selectarr, 'key')))) != count($selectarr)) {
                    $this->error("请不要输入重复选项!【选项不区分大小写】");
                }
                if (count(array_unique(array_column($selectarr, 'value'))) != count($selectarr)) {
                    $this->error("请不要输入重复选项答案!");

                }
                if ($type == '1') {
                    $params['answer'] = implode(',', $params['answer']);
                }


                if (empty($params['answer']) && $params['answer'] != '0') {
                    $this->error("请选择正确答案!");
                }
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
                $type = intval($params['type']) - 1;
                $params['selectdata'] = $params['selectdata' . $type];
                $selectarr = json_decode($params['selectdata'], true);
                $params['selectnumber'] = count($selectarr);
                $params['answer'] = $params['answer' . $type];


                foreach ($selectarr as $key => $value) {
                    if (empty($value['key']) && $value['key'] != '0') {
                        $this->error("请填写选项" . ($key + 1));
                    }
                    if (empty($value['value']) && $value['value'] != '0') {
                        $this->error("请填写选项" . ($key + 1) . "答案内容");
                    }
                    unset($selectarr[$key]['checked']);


                }
                $params['selectdata'] = json_encode($selectarr);


                if (count(array_unique(array_map('strtolower', array_column($selectarr, 'key')))) != count($selectarr)) {
                    $this->error("请不要输入重复选项!【选项不区分大小写】");

                }

                if (count(array_unique(array_column($selectarr, 'value'))) != count($selectarr)) {
                    $this->error("请不要输入重复选项答案!");

                }
                if ($type == '1') {
                    $params['answer'] = implode(',', $params['answer']);
                }


                if (empty($params['answer']) && $params['answer'] != '0') {
                    $this->error("请选择正确答案!");
                }
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

        $selectdata = [
            [
                ['key' => 'A', 'value' => ''],
                ['key' => 'B', 'value' => ''],
                ['key' => 'C', 'value' => ''],
                ['key' => 'D', 'value' => ''],
            ],
            [
                ['key' => 'A', 'value' => ''],
                ['key' => 'B', 'value' => ''],
                ['key' => 'C', 'value' => ''],
                ['key' => 'D', 'value' => ''],

            ],
            [
                ['key' => 'A', 'value' => '对'],
                ['key' => 'B', 'value' => '错'],

            ],
        ];
        $select_arr = json_decode($row['selectdata'], true);
        $row['answer'] = explode(',', $row['answer']);

        foreach ($select_arr as $key => $value) {
            $select_arr[$key]['checked'] = in_array($value['key'], $row['answer']) ? "checked" : "";
        }
        if (count($select_arr) > 0)
            $selectdata[intval($row['type']) - 1] = $select_arr;

        $this->view->assign("selectdata", $selectdata);
        $this->view->assign("row", $row);
        return $this->view->fetch();
    }


    /**
     * 导入
     */
    public function import()
    {
        $file = $this->request->request('file');
        $type_A = '1,2,3';
        $type_B = '单选题,多选题,判断题';
        $type = array_combine(explode(',',$type_A),explode(',',$type_B));
        
        $subject = new \app\admin\model\kaoshi\KaoshiSubject;
        $subject_info = $subject->field('id,subject_name')->where(['company_id'=>$this->auth->company_id])->order('id', 'asc')->select(); 
        $subject_name = array_column($subject_info , 'subject_name');//将部门名称装入一维数组，免得重复读表
        $subject_id = array_column($subject_info , 'id');//获取部门ID，主键
        $subject_info = array_combine($subject_id,$subject_name);//将两个一维数组组装成键名=》键值形式
        
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
                	$array_selectdata = explode(";",$row['selectdata']);
                	$x = [];
                	foreach($array_selectdata as $k=>$v){             		
                		$y=[];
                		$y['key'] = substr($v,0,strpos($v,':'));
                		$y['value'] = substr($v,strpos($v,':')+1,strlen($v));
                		$x[] = $y;
                	}
                	$row['selectdata']=json_encode($x);
                	$row['company_id'] = $this->auth->company_id;
                	$row['type'] = array_search($row['type'],$type);//类型转换
                	$row['admin_id'] = $this->auth->id;//出题人
                	$row['subject_id'] = array_search($row['subject_id'],$subject_info);//科目名称转换
                	$row['createtime'] = time();
                	
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
                if ($key == 'admin_id') {
                    $has_admin_id = true;
                    break;
                }
            }
            if ($has_admin_id) {
                $auth = Auth::instance();
                foreach ($insert as &$val) {
                    if (!isset($val['admin_id']) || empty($val['admin_id'])) {
                        $val['admin_id'] = $auth->isLogin() ? $auth->id : 0;
                    }
                }
            }
            $this->model->saveAll($insert);
        } catch (PDOException $exception) {
            $msg = $exception->getMessage();
            if (preg_match("/.+Integrity constraint violation: 1062 Duplicate entry '(.+)' for key '(.+)'/is", $msg, $matches)) {
                $msg = "导入失败，包含【{$matches[1]}】的记录已存在";
            };
            $this->error($msg);
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }

        $this->success();
    }
    /**
     * 真实删除
     */
    public function destroy($ids = "")
    {
        if (!$this->request->isPost()) {
            $this->error(__("Invalid parameters"));
        }
        $ids = $ids ? $ids : $this->request->post("ids");
        $pk = $this->model->getPk();
        $adminIds = $this->getDataLimitAdminIds();
        if (is_array($adminIds)) {
            $this->model->where($this->dataLimitField, 'in', $adminIds);
        }
        if ($ids) {
            $this->model->where($pk, 'in', $ids);
        }
        $count = 0;
        Db::startTrans();
        try {
            $list = $this->model->onlyTrashed()->select();
            foreach ($list as $k => $v) {
                $count += $v->delete(true);
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
        $this->error(__('Parameter %s can not be empty', 'ids'));
    }

    /**
     * 还原
     */
    public function restore($ids = "")
    {
        if (!$this->request->isPost()) {
            $this->error(__("Invalid parameters"));
        }
        $ids = $ids ? $ids : $this->request->post("ids");
        $pk = $this->model->getPk();
        $adminIds = $this->getDataLimitAdminIds();
        if (is_array($adminIds)) {
            $this->model->where($this->dataLimitField, 'in', $adminIds);
        }
        if ($ids) {
            $this->model->where($pk, 'in', $ids);
        }
        $count = 0;
        Db::startTrans();
        try {
            $list = $this->model->onlyTrashed()->select();
            foreach ($list as $index => $item) {
                $count += $item->restore();
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
        }
        $this->error(__('No rows were updated'));
    }

}