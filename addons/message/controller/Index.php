<?php

namespace addons\message\controller;


use app\admin\model\message\Info as message;
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
  
}
