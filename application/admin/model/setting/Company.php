<?php

namespace app\admin\model\setting;

use think\Model;


class Company extends Model
{

    

    

    // 表名
    protected $name = 'company_info';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'company_regtime_text',
        'company_status_text',
        'company_type_text'
    ];
    

    
    public function getCompanyStatusList()
    {
        return ['0' => __('Company_status 0'), '1' => __('Company_status 1')];
    }

    public function getCompanyTypeList()
    {
        return ['0' => __('Company_type 0'), '1' => __('Company_type 1'), '2' => __('Company_type 2')];
    }


    public function getCompanyRegtimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['company_regtime']) ? $data['company_regtime'] : '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }


    public function getCompanyStatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['company_status']) ? $data['company_status'] : '');
        $list = $this->getCompanyStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getCompanyTypeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['company_type']) ? $data['company_type'] : '');
        $list = $this->getCompanyTypeList();
        return isset($list[$value]) ? $list[$value] : '';
    }

    protected function setCompanyRegtimeAttr($value)
    {
        return $value === '' ? null : ($value && !is_numeric($value) ? strtotime($value) : $value);
    }


}
