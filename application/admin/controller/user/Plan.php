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
class Plan extends Backend
{

    protected $relationSearch = true;
    protected $dataLimit = 'personal';
	 protected $dataLimitField = 'company_id';
    protected $searchFields = 'id,username,nickname';
    protected $noNeedRight = ['jstree'];

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
     * 选择培训
     */
    public function selectmain($ids = null)
    {
        
        if ($this->request->isPost()) {
          	$params = $this->request->param();//接收过滤条件
          	$result = '';
          	$user = explode(",",$params['ids_user']);//因为传递过来的ids_user是字符不是数组，所以要用explode将其转为数组
          	//$main = $params['ids_main'];  //将传送过来的ids_main改名为$main
          	//将原main表中的user_ids和id读到二维数组中，不用每次都读表
          	$main = new \app\admin\model\training\Main_s;
          	$main_info = $main->field('id,user_ids')->where(['company_id'=>$this->auth->company_id])->where('id','in',$params['ids_main'])->select();
          	$main_id = array_column($main_info,'id');
          	$main_user_ids = array_column($main_info,'user_ids');
          	$main_array = array_combine($main_id,$main_user_ids);
          	
          	$main_data =[];
          	foreach ($params['ids_main'] as $k => $v) {
          		$data = [];
          		$main_ids = explode(",",$main_array[$v]);
          		$user_ids = $user;
          		$ids_finnal = array_diff($user_ids,$main_ids);//求两数组的差集，即是要添加去main表的user_ids中去的
               if(count($ids_finnal)>0) {
               	$data['id'] = $v;
               	if(count($main_ids)>1) {
               		$data['user_ids'] = implode(',',$main_ids).','.implode(',',$ids_finnal);
               	}else {
               		$data['user_ids'] = implode(',',$ids_finnal);
               	}
               	$main_data[] = $data;
           		}
            }	
            if($main_data) {
           	 	$result = $main->saveall($main_data);
           	 	if($result) {
          		  $this->success('完成！');
        			}
             	$this->error('保存失败');
         	} else {
         		$this->success('没有被修改的信息');
         	}
       }
        $this->view->assign("ids", $ids);
        $this->assignconfig("ids", $ids);
        return $this->view->fetch();
    }
    
     /**
     * 全清培训
     */
    public function clearmain($ids = null)
    {
    	if ($this->request->isPost()) { 	
    		$main = new \app\admin\model\training\Main_s;
    		$main_info = $main->field('id,user_ids')->where('company_id',$this->auth->company_id)->select();	
    		$main_data=[];
       	foreach ($main_info as $k => $v) {
       		$data = [];
       		$data['id'] = $v['id'];
       		$data['user_ids'] = implode(',',array_diff(explode(',',$v['user_ids']),$ids));
       		$main_data[]= $data;
       	}
           if($main_data) {
           	 	$result = $main->saveall($main_data);
           	 	if($result) {
          		  $this->success('完成！');
        			}
             	$this->error('保存失败');
         	} else {
         		$this->success('没有被修改的信息');
         	}
            
    	}  
    }
    
     /**
     * 选择考试
     */
    public function selectkaoshi($ids = null)
    {
        if ($this->request->isPost()) {
          	$params = $this->request->param();//接收过滤条件
          	$result = '';
          	$user = explode(",",$params['ids_user']);//因为传递过来的ids_user是字符不是数组，所以要用explode将其转为数组
          //	$user = explode(",",$params['ids_kaoshi']);//因为传递过来的ids_user是字符不是数组，所以要用explode将其转为数组
            Db::startTrans();
                try {
                	 
                	  $plan = [];
          			  foreach ($params['ids_kaoshi'] as $k => $v) {
          		        foreach ($user as $m => $n) {
          		         	Db::name('KaoshiUserPlan')->where(['user_id'=>$n,'plan_id'=>$v])->delete();
          						array_push($plan, ['plan_id' => $v, 'user_id' => $n,'company_id'=>$this->auth->company_id]);			
         	   			}	
            			}	
                    $result = Db::name('KaoshiUserPlan')->insertAll($plan);
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

           	 	if($result) {
          		  $this->success('完成！');
        			}
             	$this->error('保存失败');
         }
        $this->view->assign("ids", $ids);
        $this->assignconfig("ids", $ids);
        return $this->view->fetch();
    }
     /**
     * 全清考试
     */
    public function clearkaoshi($ids = null)
    {
        if ($this->request->isPost()) { 	
    		$user_plan = new \app\admin\model\kaoshi\examination\KaoshiUserPlan;
    		$result = $user_plan->where('user_id','in',$ids)->where(['status'=>0,'company_id'=>$this->auth->company_id])->delete();//仅清除未完成的考试，对于已经完成的考试，将不被清除
    		if($result) {
    			$this->success('完成');
    		}
    		$this->error('取消考试失败');
    		
    	}  
    }
}
