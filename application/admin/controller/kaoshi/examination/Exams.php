<?php

namespace app\admin\controller\kaoshi\examination;

use app\common\controller\Backend;
use app\common\library\Auth;
use Think\Db;
use fast\Tree;

/**
 *
 *
 * @icon fa fa-circle-o
 */
class Exams extends Backend
{

    /**
     * Exams模型对象
     * @var \app\admin\model\kaoshi\examination\KaoshiExams
     */
    protected $model = null;
    protected $dataLimit = 'personal';
	 protected $dataLimitField = 'company_id';
	 protected $noNeedRight = ['getquestion','select_question'];

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\kaoshi\examination\KaoshiExams;
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
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if ($params) {
                $params = $this->preExcludeFields($params);

                if ($this->dataLimit && $this->dataLimitFieldAutoFill) {
                    $params[$this->dataLimitField] = $this->auth->company_id;
                }
                $result = false;
                $params['admin_id'] = $this->auth->id;


                $question_obj = new \app\admin\model\kaoshi\examination\KaoshiQuestions;
                $LevelList = $question_obj->getLevelList();
                $typenum = [];
                foreach ($LevelList as $key => $value) {
                    $typenum[$key] = Db::name('KaoshiQuestions')
                        ->where(['deletetime' => NULL, 'subject_id' => $params['subject_id'], 'level' => $key, 'status' => 1])
                        ->group('type')
                        ->column(['type', 'count(id)' => 'num']);
                }


                $namearr = $this->model->where(['deletetime' => NULL, 'subject_id' => $params['subject_id']])->column('exam_name');
                if (in_array($params['exam_name'], $namearr)) {
                    $this->error('卷名已存在！');

                }
                $params['score'] = 0;
                $settingdata = json_decode($params['settingdata'], true);
                if (count($settingdata) < 1) {
                    $this->error('请添加考卷设置');

                }
                $question_ids = '';

                foreach ($settingdata as $key => $value) {
                    $num = intval($value['number']);
                    $mark = intval($value['mark']);
                    if (intval($value['number']) <= 0) {
                        $this->error('考卷设置第' . ($key + 1) . '项数量需大于0');
                    }
                    if (intval($value['mark']) <= 0) {
                        $this->error('考卷设置第' . ($key + 1) . '项分值需大于0');
                    }

                    if (is_array($typenum[$value['level']])) {
                        if (count($typenum[$value['level']]) == 0) {
                            $this->error('考卷设置第' . ($key + 1) . '项，该科目无[' . $LevelList[$value['level']] . ']级别题目');
                        }
                        if (!isset($typenum[$value['level']][$value['type']])) {
                            $this->error('考卷设置第' . ($key + 1) . '项，[' . $LevelList[$value['level']] . ']级别题目没有该题型');
                        } elseif ($typenum[$value['level']][$value['type']] < $value['number']) {
                            $this->error('考卷设置第' . ($key + 1) . '项数量过多[题库仅' . $typenum[$value['level']][$value['type']] . '题]');
                        }
                    }

                    $params['score'] += $num * $mark;


//                    自定义组卷操作
                    $question_ids .=($question_ids=='')?$value['question_ids']:','.$value['question_ids'];

                }
//                    自定义组卷操作
                if($params['type'] == 2){
                    $question_ids = implode(',',array_unique(explode(',',$question_ids)));
                    $questions = Db::name('KaoshiQuestions')->where(['id'=>['in',$question_ids]])
                        ->column('id,question,selectdata,type,annex,answer,selectnumber,describe,level');
                    $questionsdata = $settingdata;
                    foreach ($questionsdata  as $k=>$item){
                        $ids = explode(',',$item['question_ids']);
                        $timu = [];
                        foreach ($ids as $v){
                            array_push($timu,$questions[$v]);
                        }
                        $questionsdata[$k] = array_merge($questionsdata[$k],['timu'=>$timu]);
                    }
                    $params = array_merge($params,['questionsdata'=>json_encode($questionsdata)]);
                }
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
        $subject_id = $this->request->request("subject_id",'');
        $this->view->assign("subject_id", $subject_id);
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
                $question_obj = new \app\admin\model\kaoshi\examination\KaoshiQuestions;
                $LevelList = $question_obj->getLevelList();
                $typenum = [];
                $question_ids = '';

                foreach ($LevelList as $key => $value) {
                    $typenum[$key] = Db::name('KaoshiQuestions')
                        ->where(['deletetime' => NULL, 'subject_id' => $params['subject_id'], 'level' => $key, 'status' => 1])
                        ->group('type')
                        ->column(['type', 'count(id)' => 'num']);
                }


                $params['score'] = 0;
                $settingdata = json_decode($params['settingdata'], true);
                if (count($settingdata) < 1) {
                    $this->error('请添加考卷设置');

                }
                foreach ($settingdata as $key => $value) {
                    $num = intval($value['number']);
                    $mark = intval($value['mark']);
                    if (intval($value['number']) <= 0) {
                        $this->error('考卷设置第' . ($key + 1) . '项数量需大于0');
                    }
                    if (intval($value['mark']) <= 0) {
                        $this->error('考卷设置第' . ($key + 1) . '项分值需大于0');
                    }
                    if (is_array($typenum[$value['level']])) {
                        if (count($typenum[$value['level']]) == 0) {
                            $this->error('考卷设置第' . ($key + 1) . '项，该科目无[' . $LevelList[$value['level']] . ']级别题目');
                        }
                        if (!isset($typenum[$value['level']][$value['type']])) {
                            $this->error('考卷设置第' . ($key + 1) . '项，[' . $LevelList[$value['level']] . ']级别题目没有该题型');
                        } elseif ($typenum[$value['level']][$value['type']] < $value['number']) {
                            $this->error('考卷设置第' . ($key + 1) . '项数量过多[题库仅' . $typenum[$value['level']][$value['type']] . '题]');
                        }
                    }

                    $params['score'] += $num * $mark;
                    //                    自定义组卷操作
                    $question_ids .=($question_ids=='')?$value['question_ids']:','.$value['question_ids'];

                }
//                    自定义组卷操作
                if($params['type'] == 2){
                    $question_ids = implode(',',array_unique(explode(',',$question_ids)));
                    $questions = Db::name('KaoshiQuestions')->where(['id'=>['in',$question_ids]])
                        ->column('id,question,selectdata,type,annex,answer,selectnumber,describe,level');
                    $questionsdata = $settingdata;

                    foreach ($questionsdata  as $k=>$item){
                        if (strlen($item['question_ids'])==0){
                            $this->error('考卷设置第' . ($k + 1) . '项，未选择题目！');
                        }
                        $ids = explode(',',$item['question_ids']);
                        $timu = [];
                        foreach ($ids as $v){
                            array_push($timu,$questions[$v]);
                        }
                        $questionsdata[$k] = array_merge($questionsdata[$k],['timu'=>$timu]);
                    }
                    $params = array_merge($params,['questionsdata'=>json_encode($questionsdata)]);
                }

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

        $this->view->assign("row", $row);
        return $this->view->fetch();
    }
	  /**
     * 预览试卷
     */
    public function getquestion($ids = null)
    {
        $row = $this->model->get($ids);
        $settingdata = json_decode($row['settingdata'], true);
        $questionsdata = json_decode($row['questionsdata'], true);
        $this->view->assign("row", $row);
        $questions = [];
        $question_obj = Db::name('KaoshiQuestions');
        if($row['type'] == 1){
            foreach ($settingdata as $key => $value) {
                $map['type'] = $value['type'];
                $map['subject_id'] = $row['subject_id'];
                $map['level'] = $value['level'];
                $map['status'] = 1;
                $map['deletetime'] = null;
                $arr = $question_obj->where($map)->select();
                if($value['number']>count($arr))$this->error('题目不足,请修改题目设置');
                $rand_arr = array_rand($arr, intval($value['number']));
                if (is_array($rand_arr)) {
                    foreach ($rand_arr as $k => $v) {
                        $arr[$v]['selectdata'] = json_decode($arr[$v]['selectdata'], true);
                        if ($value['type'] == 2) {
                            $arr[$v]['answer'] = explode(',', $arr[$v]['answer']);
                        }
                        $questions[$key][$k] = $arr[$v];
                    }

                    shuffle($questions[$key]);
                } else {
                    $arr[$rand_arr]['selectdata'] = json_decode($arr[$rand_arr]['selectdata'], true);
                    if ($value['type'] == 2) {
                        $arr[$rand_arr]['answer'] = explode(',', $arr[$rand_arr]['answer']);

                    }
                    $questions[$key][] = $arr[$rand_arr];
                }

            }

        }elseif ($row['type']==2){
            foreach ($questionsdata as $v){
                foreach ($v['timu'] as $k=>$timu){
                    $v['timu'][$k]['selectdata'] = json_decode($timu['selectdata'],true);
                }
                array_push($questions,$v['timu']) ;
            }
        }
//        halt($questions);
        $this->view->assign("typeList", ["1" => "单选题", "2" => "多选题", "3" => "判断题",]);

        $this->view->assign("questions", $questions);

        return $this->view->fetch();


    }
	  /**
     * 选择试题
     */
    public function select_question(){

        if($this->request->isPost()){
            $params = $this->request->param();
            if(isset($params['ids'])){$this->success();}

            if(isset($params['level']) && isset($params['subject_id']) && isset($params['type']) ){

                $map = [
                    'deletetime' => NULL,
                    'level' => $params['level'],
                    'subject_id' => $params['subject_id'],
                    'type' => $params['type'],
                    'status' => 1];
                $questions = Db::name('KaoshiQuestions')
                    ->where($map)->select();
                foreach ($questions as $key=>$val) {
                    $select_data = json_decode($val['selectdata'],true);
                    foreach ($select_data as $k=>$v){
                        if($v['key'] == $val['answer']) $questions[$key] = array_merge($questions[$key],['answer_text'=>$v['value']]);
                    }
                    $questions[$key]['selectdata'] = $select_data;
                }

                return $questions;

            }

        }


        return $this->view->fetch();
    }


}
