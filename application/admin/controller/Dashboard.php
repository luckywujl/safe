<?php

namespace app\admin\controller;

use app\admin\model\Admin;
use app\admin\model\User;
use app\admin\model\training\Main;
use app\admin\model\kaoshi\examination\KaoshiPlan;
use app\admin\model\trouble\trouble\Main as TroubleMain;
use app\common\controller\Backend;
use app\common\model\Attachment;
use fast\Date;
use think\Db;

/**
 * 控制台
 *
 * @icon   fa fa-dashboard
 * @remark 用于展示当前系统中的统计数据、统计报表及重要实时数据
 */
class Dashboard extends Backend
{

    /**
     * 查看
     */
    public function index()
    {
        try {
            \think\Db::execute("SET @@sql_mode='';");
        } catch (\Exception $e) {

        }
        $column = [];
        $starttime = Date::unixtime('day', -6);
        $endtime = Date::unixtime('day', 0, 'end');
        $joinlist = Db("user")->where('jointime', 'between time', [$starttime, $endtime])
            ->field('jointime, status, COUNT(*) AS nums, DATE_FORMAT(FROM_UNIXTIME(jointime), "%Y-%m-%d") AS join_date')
            ->group('join_date')
            ->select();

        $trouble = Db("trouble_main")->where('createtime','between time',[$starttime,$endtime])
            ->field('createtime,COUNT(*) AS nums,DATE_FORMAT(FROM_UNIXTIME(createtime),"%Y-%m-%d") AS create_date')
            ->group('create_date')
            ->select();

        for ($time = $starttime; $time <= $endtime;) {
            $column[] = date("Y-m-d", $time);
            $time += 86400;
        }

        $troublelist = array_fill_keys($column,0);
        foreach($trouble as $k=>$v){
            $troublelist[$v['create_date']] =$v['nums'];
        }
        $userlist = array_fill_keys($column, 0);
        foreach ($joinlist as $k => $v) {
            $userlist[$v['join_date']] = $v['nums'];
        }

        $dbTableList = Db::query("SHOW TABLE STATUS");
        $this->view->assign([
            'totaluser'       => User::count(),
            'totaladdon'      => Main::where('company_id',$this->auth->company_id)->count(),
            'totaladmin'      => Admin::where('company_id',$this->auth->company_id)->count(),
            'totalcategory'   => KaoshiPlan::where('company_id',$this->auth->company_id)->count(),
            'totaltrouble'		=> TroubleMain::where('company_id',$this->auth->company_id)->count(),
            'totaltrouble_in' => TroubleMain::where(['company_id'=>$this->auth->company_id,'main_status'=>['not in','0,7,8,9']])->count(),
            'totaltrouble_ok' => TroubleMain::where(['company_id'=>$this->auth->company_id,'main_status'=>['in','0,7,8,9']])->count(),
            'todayusersignup' => User::whereTime('jointime', 'today')->count(),
            'todayuserlogin'  => User::whereTime('logintime', 'today')->count(),
            'sevendau'        => User::whereTime('jointime|logintime|prevtime', '-7 days')->count(),
            'thirtydau'       => User::whereTime('jointime|logintime|prevtime', '-30 days')->count(),
            'threednu'        => User::whereTime('jointime', '-3 days')->count(),
            'sevendnu'        => User::whereTime('jointime', '-7 days')->count(),
            'dbtablenums'     => count($dbTableList),
            'dbsize'          => array_sum(array_map(function ($item) {
                return $item['Data_length'] + $item['Index_length'];
            }, $dbTableList)),
            'attachmentnums'  => Attachment::count(),
            'attachmentsize'  => Attachment::sum('filesize'),
            'picturenums'     => Attachment::where('mimetype', 'like', 'image/%')->count(),
            'picturesize'     => Attachment::where('mimetype', 'like', 'image/%')->sum('filesize'),
        ]);

        $this->assignconfig('column', array_keys($troublelist));
        $this->assignconfig('userdata', array_values($troublelist));

        return $this->view->fetch();
    }

}
