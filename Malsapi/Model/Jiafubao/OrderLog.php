<?php

class Model_Jiafubao_OrderLog extends PhalApi_Model_NotORM {

    protected function getTableName($id) {
        return 'jfb_order_log';
    }
}
