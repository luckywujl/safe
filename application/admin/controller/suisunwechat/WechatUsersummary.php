<?php

namespace app\admin\controller\suisunwechat;

use app\common\controller\Backend;

/**
 * 微信用户数据分析
 *
 * @icon fa fa-circle-o
 */
class WechatUsersummary extends Backend
{
    
    /**
     * WechatUsersummary模型对象
     * @var \app\admin\model\suisunwechat\WechatUsersummary
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\suisunwechat\WechatUsersummary;

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

        $isFind = $this->model->count();
        if(empty($isFind)){
            return $this->redirect('init_summary');
        }
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model
                ->where($where)
                ->order($sort, $order)
                ->count();

            $list = $this->model
                ->where($where)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();

            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list, 'chartData' => []);

            return json($result);
        }

        $yesterdaydate = $this->model->whereTime('time','yesterday')->find();
        $app = \addons\suisunwechat\library\Wechat::instance()->getApp();
        if(empty($yesterdaydate)){
            $start_time = date("Y-m-d",strtotime("-2 day"));
            $end_time = date("Y-m-d",strtotime("-1 day"));
            $res = $app->data_cube->userSummary($start_time,$end_time);
            if(isset($res['list'])){
                $this->updateSummary($res['list']);
            }
        }

        $yesterdaydate = $this->model->whereTime('time','yesterday')->find();
        $this->assign('date',$yesterdaydate);
        $this->assign('yesterday',date('Y-m-d',time()-86400));
        return $this->view->fetch();
    }


    /**
     * 数据采集
     */
    public function init_summary(){
        if($this->request->isAjax()){
            //开始采集数据
            $app = \addons\suisunwechat\library\Wechat::instance()->getApp();
            $start = $this->request->param('start');
            $end = $this->request->param('end');
            $res = $app->data_cube->userSummary($start,$end);
            if(isset($res['list'])){
                $this->updateSummary($res['list']);
            }
            $this->success('获取成功','',$res);
        }
        return $this->fetch();
    }

    protected function updateSummary($list){
        foreach ($list as $item){
            $add_item = [
                'user_source'=>$item['user_source'],
                'new_user'=>$item['new_user'],
                'cancel_user'=>$item['cancel_user'],
                'time'=>strtotime($item['ref_date']),
            ];
            $model = new \app\admin\model\suisunwechat\WechatUsersummary();
            $isData = $model->where(['time'=>$add_item['time']])->find();
            if(!$isData){
                $model->save($add_item);
            }
        }
    }

    public function getData(){
        $starttime = $this->request->get('starttime',date('Y-m-d'));
        $endtime = $this->request->get('endtime',date('Y-m-d'));

        $starttime = strtotime(substr($starttime,0,10));
        $endtime = strtotime(substr($endtime,0,10));

        $model = new \app\admin\model\suisunwechat\WechatUsersummary();

        $data = ['new_count' => [],'cancel_count' => [],'purecount' => [],'date' => []];
        $days = ($endtime - $starttime) / 86400;
        if ($days == 0){
            $starttime = time();
        }
        for ($i = 0;$i <= $days;$i++){
            $yesterday = strtotime('+'.$i.'days',$starttime);
            $date = strtotime('+1day',$yesterday);
            $data['new_count'][] = $model
                ->where('time',$yesterday)
                ->sum('new_user');
            $data['cancel_count'][] = $model
                ->where('time',$yesterday)
                ->sum('cancel_user');
            $data['date'][] = date('Y-m-d',$yesterday);
        }

        for ($i = 0;$i < count($data['cancel_count']);$i++){
            $data['purecount'][] = $data['new_count'][$i] - $data['cancel_count'][$i];
        }
        $this->success('','',$data);
    }
}
