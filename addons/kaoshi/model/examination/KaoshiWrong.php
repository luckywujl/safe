<?php

namespace addons\kaoshi\model\examination;

use think\Model;

class KaoshiWrong extends Model
{

    // 表名
    protected $name = 'kaoshi_wrong';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    protected $deleteTime = false;


    public function user()
    {
        return $this->hasOne('app\admin\model\User', 'id', 'user_id')->setEagerlyType(0)->joinType('LEFT');
    }

    public function subject()
    {
        return $this->hasOne('app\admin\model\KaoshiSubject', 'id', 'subject_id')->setEagerlyType(0)->joinType('LEFT');
    }


}
