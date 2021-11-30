<?php

namespace addons\message\controller;


use app\admin\model\message\Info as message;
use app\admin\model\message\Category as category;
use app\common\model\User;

class Index extends Base
{

    /**
     * 无需登录的方法,同时也就不需要鉴权了
     * @var array
     */
    protected $noNeedLogin = [];

    /**
     * 无需鉴权的方法,但需要登录
     * @var array
     */
    protected $noNeedRight = ['*'];
    protected $relationSearch = true;
    protected $group_id = null;
    protected $user_id = null;
    public function _initialize()
    {
        parent::_initialize();
        $this->group_id = User::where('id', $this->auth->id)->value('group_id');
        $this->user_id = $this->auth->id;
        $this->company_id = $this->auth->company_id;
        
        
        $type = [
        			[
        				'id'=>0,
        				'name'=>'未读'
        			],
        			[
        				'id'=>1,
        				'name'=>'已读'
        			],
        			[
        				'id'=>2,
        				'name'=>'全部'
        			]
        ];
        
        $this->view->assign("typeList", $type);
       
        
    }

    public function index()
    {
        if ($this->request->isAjax()) {
            $model = new message;
            //$group = $this->request->param('group', 'learning');
            $year = $this->request->param('year', date('Y'));
            $category_id = $this->request->param('category_id', '1');
            $time = date("Y-m-d H:i:s");
            list($where, $sort, $order, $offset, $limit) = $this->buildparams(null);
            $total = $model
                ->where($where)
                ->where('company_id',$this->auth->company_id);
            if ($category_id=='0') {
                $total = $total->where(function ($query) {
                	$query->where('not find_in_set('.$this->user_id.',user_r_ids)');
            		});
            }
            if ($category_id=='1') {
                $total = $total->where(function ($query) {
                	$query->where('find_in_set('.$this->user_id.',user_r_ids)');
            		});
            }
            
            if ($year !== '') {
                $total = $total->whereTime('createtime', 'between', ["{$year}-1-1","{$year}-12-31"]);
            }
                
            $total = $total->where(function ($query) {
                	$query->where('find_in_set('.$this->user_id.',user_ids)')->whereor('find_in_set('.$this->group_id.',user_group_ids)');
            		})
                ->order($sort, $order)
                ->count();
            $list = $model
                ->where($where)
                ->where('company_id',$this->auth->company_id);
                
            if ($category_id=='0') {
                $list = $list->where(function ($query) {
                	$query->where('not find_in_set('.$this->user_id.',user_r_ids)');
            		});
            }
            if ($category_id=='1') {
                $list = $list->where(function ($query) {
                	$query->where('find_in_set('.$this->user_id.',user_r_ids)');
            		});
            }
            if ($year !== '') {
                $list = $list->whereTime('createtime', 'between', ["{$year}-1-1","{$year}-12-31"]);
            }    
            $list = $list->where(function ($query) {
                	$query->where('find_in_set('.$this->user_id.',user_ids)')->whereor('find_in_set('.$this->group_id.',user_group_ids)');
            		})
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();
            $result = array("total" => $total, "rows" => $list);

            return json($result);
            
            
        }

        
        return $this->view->fetch('/index');
    }
    public function getCount()
    {
            $model = new message;
            $year = $this->request->param('year', date('Y'));
            $time = date("Y-m-d H:i:s");
            $count=[];
            $map = [];
            if ($year !== '') {
                $map['createtime']=['between time',["{$year}-1-1","{$year}-12-31"]];
            }
            
            $total = $model
            		->where($map)
            		->where('company_id',$this->auth->company_id)
            		->where(function ($query) {
                		$query->where('find_in_set('.$this->user_id.',user_ids)')->whereor('find_in_set('.$this->group_id.',user_group_ids)');
            		});
            		
            $type = [
        			[
        				'id'=>0,
        				'name'=>'未读'
        			],
        			[
        				'id'=>1,
        				'name'=>'已读'
        			],
        			[
        				'id'=>2,
        				'name'=>'全部'
        			]
        		];
        		
            $count['0'] = $model
            		->where($map)
            		->where('company_id',$this->auth->company_id)
            		->where(function ($query) {
                		$query->where('find_in_set('.$this->user_id.',user_ids)')->whereor('find_in_set('.$this->group_id.',user_group_ids)');
            		})
            		->where(function ($query) {
                	$query->where('not find_in_set('.$this->user_id.',user_r_ids)');
            		})
            		->count();
            $count['1'] = $model
            		->where($map)
            		->where('company_id',$this->auth->company_id)
            		->where(function ($query) {
                		$query->where('find_in_set('.$this->user_id.',user_ids)')->whereor('find_in_set('.$this->group_id.',user_group_ids)');
            		})
            		->where(function ($query) {
                	$query->where('find_in_set('.$this->user_id.',user_r_ids)');
            		})
            		->count();
            $count['2'] = $model
            		->where($map)
            		->where('company_id',$this->auth->company_id)
            		->where(function ($query) {
                		$query->where('find_in_set('.$this->user_id.',user_ids)')->whereor('find_in_set('.$this->group_id.',user_group_ids)');
            		})
            		->count();
             
            if ($this->request->isAjax()) {
                return json($count);
            }else{
                return $count;
            }
    }
    public function addids_r($id = null)  //已阅读
    {
    	if ($this->request->isPost()) { 
    	   $params = $this->request->param();//接收过滤条件	
    		$model = new message;
    		$message = $model->field('id,user_r_ids')->where(['company_id'=>$this->auth->company_id,'id'=>$params['id']])->find();	
    		
    		$message_data =[];
         $message_ids = explode(",",$message['user_r_ids']);
         $user_ids = explode(",",$this->auth->id);
         $ids_finnal = array_diff($user_ids,$message_ids);//求两数组的差集，即是要添加去main表的user_ids中去的
         if(count($ids_finnal)>0) {
             $message_data['id'] = $params['id'];
             if(count($message_ids)>1) {
               $message_data['user_r_ids'] = implode(',',$message_ids).','.implode(',',$ids_finnal);
             }else {
               $message_data['user_r_ids'] = implode(',',$ids_finnal);
             }
             
         }
           	
            if($message_data) {
           	 	$result = $message->save($message_data);
           	 	if($result) {
          		  $this->success('完成！');
        			}
             	$this->error('保存失败');
         	} else {
         		$this->success('没有被修改的信息');
         	}
    		$this->success('签阅完成！');
   
    	}  
    }
    public function showmessage()
    {
        $model = new message;
        $category = new category;
        $id = $this->request->param('id');
        
        $message = $model->where(['id'=>$id,'company_id'=>$this->auth->company_id])->find();	
        $category_info = $category->where(['id'=>$message['category_id'],'company_id'=>$this->auth->company_id])->find();
        $message['category_id'] = $category_info['name'];
        $this->view->assign('message', $message);
       
        return $this->view->fetch('/showmessage');
    }
  
}
