<?php

class Model_Zhianbao_SmsCheckCode extends PhalApi_Model_NotORM {

    protected function getTableName($id) {
        return 'zab_sms_checkcode';
    }
}
