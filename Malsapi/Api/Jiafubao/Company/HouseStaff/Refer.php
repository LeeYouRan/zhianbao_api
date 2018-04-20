<?php
/**
 * 默认接口服务类
 *
 * @author: Dm
 */
class Api_Jiafubao_Company_HouseStaff_Refer extends PhalApi_Api {
    
    public function getRules() {
        return array (
                 'Go' => array(
                     'companyId' => array('name' => 'company_id', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '公司ID'),
                     'staffId' => array('name' => 'staff_id', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '员工ID'),
            ),
        );
    }
  
  /**
     * 获取家政员工资料信息
     * #desc 用于获取家政员工资料信息
     * #return int code 操作码，0表示成功
   * #return int id 员工ID
  */
    public function Go() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
        //判断公司是否存在
        $domainCompany = new Domain_Zhianbao_Company();
        $companyInfo = $domainCompany->getBaseInfo($this->companyId);
        if (empty($companyInfo)) {
            $rs['code'] = 100;
            $rs['msg'] = T('Company not exists');
            return $rs;
        }

        //判断家政人员是否存在
        $houseStaffDomain = new Domain_Jiafubao_CompanyHouseStaff();
        $staffInfo = $houseStaffDomain->getBaseInfo($this->staffId);
        if( !$staffInfo) {
            $rs['code'] = 126;
            $rs['msg'] = T('Staff not exists');
            return $rs;
        }
        //判断家政员是否审核
        if($staffInfo['is_check'] == 'y'){
            $rs['code'] = 185;
            $rs['msg'] = T('Staff review');
            return $rs;
        }
        //提交审核
        $staffCheckDomain = new Domain_Jiafubao_HouseStaffCheck();
        $data = array(
            'staff_id' => $this->staffId,
            'status' => '0',
            'create_time' => time(),
            'last_modify' => time(),
        );
        $checkInfo = $staffCheckDomain->refer($data);
        if(!$checkInfo){
            $rs['code'] = 102;
            $rs['msg'] = T('Add failed');
            return $rs;
        }
        $rs['info'] = $checkInfo;

        return $rs;
    }
    
}
