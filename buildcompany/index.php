<?php
/**
 *
 * 神铺商户管理后台入口
 *
 */

require_once dirname(__FILE__) . '/init.php';

$debug = DI ()->config->get ( 'sys.debug' );
if($debug){

}else{
    @header('Access-Control-Allow-Credentials : true');
    @header('Access-Control-Allow-Origin : http://zgbuser.mshenpu.com');
}

//装载你的接口
DI()->loader->addDirs(array('Malsapi','Library'));

$api = new PhalApi();

checkLogin();

/** ---------------- 响应接口请求 ---------------- **/
if(isset($_REQUEST['service']))$_REQUEST['service'] = $_REQUEST['service'].'.Go';
$rs = $api->response();
$rs->output();

function checkLogin(){
    $noneedApiList = array('Zhianbao_Company_User_Login','Zhianbao_Company_Sms_SendCode','Zhianbao_Company_User_FindPwd','Building_Project_Join_Download');
    if(in_array($_REQUEST['service'],$noneedApiList)){
        return true;
    }


    $rs = DI()->response;
    $zabUid = DI()->cookie->get('zab_uid');
    if (empty($zabUid)) {
        $rs->setRet(200);
        $rs->setData(array('code'=>129,'msg'=>T('Please login first')));
        $rs->output();
        exit;
    }
    $sessionDomain = new Domain_Zhianbao_UserSession();
    $sessionData = $sessionDomain->checkSession($zabUid);
    if($sessionData){
        if($sessionData['parent_id'] == 0){
            $_REQUEST['user_id'] = $sessionData['user_id'];
            $_REQUEST['operate_id'] = $sessionData['user_id'];
            $_REQUEST['company_id'] = $sessionData['company_id'];
        }else{
            $_REQUEST['user_id'] = $sessionData['parent_id'];
            $_REQUEST['operate_id'] = $sessionData['user_id'];
            $_REQUEST['company_id'] = $sessionData['company_id'];
        }

//        $_REQUEST['user_id'] = $sessionData['user_id'];
//        $_REQUEST['company_id'] = $sessionData['company_id'];

    }else{
        DI()->cookie->delete('zab_uid');
        $rs->setRet(200);
        $rs->setData(array('code'=>129,'msg'=>T ('Please login first')));
        $rs->output();
        exit;
    }
}


